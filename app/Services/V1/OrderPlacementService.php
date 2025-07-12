<?php

namespace App\Services\V1;

use App\Models\V1\Order;
use App\Models\V1\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderPlacementService
{

  public function __construct(protected DeliveryZoneService $deliveryZoneService) {}

  /**
   * Place a new order after validating the delivery location.
   *
   * @param User $customer The user placing the order.
   * @param array $data The validated data from the request.
   * @return Order|null Returns the Order on success, or null if location is out of zone.
   */
  public function placeOrder(User $customer, array $data): ?Order
  {
    $restaurant = Restaurant::with('activeDeliveryZones')->findOrFail($data['restaurant_id']);
    $isServiceable = $this->deliveryZoneService->isLocationInServiceableArea(
      $data['delivery_latitude'],
      $data['delivery_longitude'],
      $restaurant->activeDeliveryZones
    );

    if (!$isServiceable) {
      return null;
    }

    return DB::transaction(function () use ($customer, $restaurant, $data) {
      $order = $customer->orders()->create([
        'restaurant_id' => $restaurant->id,
        'delivery_address' => $data['delivery_address'],
        'delivery_latitude' => $data['delivery_latitude'],
        'delivery_longitude' => $data['delivery_longitude'],
        'total_amount' => $data['total_amount'],
        'status' => 'pending'
      ]);

      return $order;
    });
  }

  /**
   * Cancels a given order.
   *
   * @param Order $order
   * @return Order
   */
  public function cancelOrder(Order $order): Order
  {
    $order->update(['status' => 'cancelled']);
    return $order;
  }
}
