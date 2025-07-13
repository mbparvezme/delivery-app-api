<?php

namespace App\Services\V1;

use App\Models\V1\Order;
use App\Models\V1\OrderAssignment;
use Illuminate\Support\Facades\DB;

class DeliveryAssignmentService
{
    /**
     * Finds the nearest available delivery person and assigns them to an order.
     *
     * @param Order $order The order to be assigned.
     * @param float $searchRadiusKm The maximum radius to search for a delivery person (e.g., 5 km).
     * @return OrderAssignment|null The new assignment record, or null if no one is found.
     */
    // public function assignOrderToNearest(Order $order, float $searchRadiusKm = 5): ?OrderAssignment
    // {
    //     $restaurant = $order->restaurant;

    //     // SQL query uses the Haversine formula to find the nearest delivery person.
    //     $nearestDeliveryMan = DB::table('delivery_men')
    //         ->select('id', DB::raw(
    //             '( 6371 * acos( cos( radians(?) ) * cos( radians( current_latitude ) ) * cos( radians( current_longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( current_latitude ) ) ) ) AS distance',
    //             [$restaurant->latitude, $restaurant->longitude, $restaurant->latitude]
    //         ))
    //         ->where('is_available', true)
    //         ->whereNotNull(['current_latitude', 'current_longitude'])
    //         ->whereNotIn('id', function ($query) use ($order) {
    //             $query->select('delivery_man_id')
    //                 ->from('order_assignments')
    //                 ->where('order_id', $order->id);
    //         })
    //         ->having('distance', '<=', $searchRadiusKm)
    //         ->orderBy('distance', 'asc')
    //         ->first();

    //     // If no available delivery person is found within the radius, return null.
    //     if (!$nearestDeliveryMan) {
    //         return null;
    //     }

    //     // Create the assignment record with a 'pending' status.
    //     $assignment = OrderAssignment::create([
    //         'order_id' => $order->id,
    //         'delivery_man_id' => $nearestDeliveryMan->id,
    //         'status' => 'pending',
    //     ]);

    //     // Here, you would typically dispatch an event to notify the delivery person.
    //     // event(new OrderAssigned($assignment));

    //     return $assignment;
    // }


    /**
     * Finds the nearest available delivery person and assigns them to an order.
     *
     * @param Order $order The order to be assigned.
     * @param float $searchRadiusKm The maximum radius to search for a delivery person (e.g., 5 km).
     * @return OrderAssignment|null The new assignment record, or null if no one is found.
     */
    public function assignOrderToNearest(Order $order, float $searchRadiusKm = 5): ?OrderAssignment
    {
        $restaurant = $order->restaurant;
        $nearestDeliveryMan = DB::table('delivery_men')
            ->select('id')
            ->selectRaw(
                'SQRT(
                    POW(RADIANS(current_longitude - ?) * COS(RADIANS((current_latitude + ?) / 2)), 2) +
                    POW(RADIANS(current_latitude - ?), 2)
                ) * 6371 AS distance',
                [$restaurant->longitude, $restaurant->latitude, $restaurant->latitude]
            )
            ->where('is_available', true)
            ->whereNotNull(['current_latitude', 'current_longitude'])
            ->whereNotIn('id', function ($query) use ($order) {
                $query->select('delivery_man_id')
                    ->from('order_assignments')
                    ->where('order_id', $order->id);
            })
            ->having('distance', '<=', $searchRadiusKm)
            ->orderBy('distance', 'asc')
            ->first();

        if (!$nearestDeliveryMan) {
            return null;
        }

        $assignment = OrderAssignment::create([
            'order_id' => $order->id,
            'delivery_man_id' => $nearestDeliveryMan->id,
            'status' => 'pending',
        ]);

        return $assignment;
    }

    /**
     * Handles the logic when a delivery person accepts an assignment.
     */
    public function acceptAssignment(OrderAssignment $assignment): OrderAssignment
    {
        return DB::transaction(function () use ($assignment) {
            $assignment->update(['status' => 'accepted']);
            $assignment->order()->update([
                'delivery_man_id' => $assignment->delivery_man_id,
                'status' => 'out_for_delivery',
            ]);
            $assignment->deliveryMan()->update(['is_available' => false]);
            return $assignment;
        });
    }

    /**
     * Handles the logic when a delivery person rejects an assignment.
     */
    public function rejectAssignment(OrderAssignment $assignment): ?OrderAssignment
    {
        $assignment->update(['status' => 'rejected']);
        return $this->assignOrderToNearest($assignment->order);
    }


}
