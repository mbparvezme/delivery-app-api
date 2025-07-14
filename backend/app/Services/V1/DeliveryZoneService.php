<?php

namespace App\Services\V1;

use App\Models\V1\Restaurant;
use App\Models\V1\DeliveryZone;
use Illuminate\Support\Collection;

class DeliveryZoneService
{
    /**
     * Create a new delivery zone for a specific restaurant.
     *
     * @param Restaurant $restaurant The restaurant to which the zone belongs.
     * @param array $data The validated data from the request.
     * @return DeliveryZone
     */
    public function createZone(Restaurant $restaurant, array $data): DeliveryZone
    {
        $zoneData = ['name' => $data['name'], 'type' => $data['type'], 'value' => $data['value']];
        $deliveryZone = $restaurant->deliveryZones()->create($zoneData);
        return $deliveryZone;
    }

    /**
     * Update an existing delivery zone.
     *
     * @param DeliveryZone $deliveryZone The zone to update.
     * @param array $data The validated data for the update.
     * @return DeliveryZone
     */
    public function updateZone(DeliveryZone $deliveryZone, array $data): DeliveryZone
    {
        $deliveryZone->update($data);
        return $deliveryZone;
    }

    /**
     * Delete a delivery zone.
     *
     * @param DeliveryZone $deliveryZone The zone to delete.
     * @return void
     */
    public function deleteZone(DeliveryZone $deliveryZone): void
    {
        $deliveryZone->delete();
    }


    /**
     * Checks if a given location is within any of a restaurant's active delivery zones.
     *
     * @param float $customerLat The customer's latitude.
     * @param float $customerLng The customer's longitude.
     * @param Collection $zones A collection of the restaurant's active delivery zones.
     * @return bool
     */
    public function isLocationInServiceableArea(float $customerLat, float $customerLng, Collection $zones): bool
    {
        foreach ($zones as $zone) {
            if ($zone->type === 'radius') {
                if ($this->isInsideRadius($customerLat, $customerLng, $zone)) {
                    return true;
                }
            } elseif ($zone->type === 'polygon') {
                if ($this->isInsidePolygon($customerLat, $customerLng, $zone)) {
                    return true;
                }
            }
        }

        return false;
    }

    // /**
    //  * Checks if a point is inside a radius zone using the Haversine formula.
    //  *
    //  * @param float $customerLat
    //  * @param float $customerLng
    //  * @param DeliveryZone $zone
    //  * @return bool
    //  */
    // private function isInsideRadius(float $customerLat, float $customerLng, DeliveryZone $zone): bool
    // {
    //     $centerLat = $zone->restaurant->latitude;
    //     $centerLng = $zone->restaurant->longitude;

    //     $radiusInMeters = $zone->value;

    //     $earthRadius = 6371000;

    //     $latFrom = deg2rad($centerLat);
    //     $lonFrom = deg2rad($centerLng);
    //     $latTo = deg2rad($customerLat);
    //     $lonTo = deg2rad($customerLng);

    //     $latDelta = $latTo - $latFrom;
    //     $lonDelta = $lonTo - $lonFrom;

    //     $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

    //     $distance = $angle * $earthRadius;

    //     return $distance <= $radiusInMeters;
    // }


    /**
     * Checks if a point is inside a radius zone using the Equirectangular approximation.
     *
     * =================
     * WHY: Fast and efficient for short distances (e.g., under 30km), treats the Earth as a flat plane
     * =================
     *
     * @param float $customerLat The latitude of the customer's location.
     * @param float $customerLng The longitude of the customer's location.
     * @param DeliveryZone $zone The delivery zone object, which must be of type 'radius'.
     * @return bool True if the customer's location is within the radius, false otherwise.
     */
    private function isInsideRadius(float $customerLat, float $customerLng, DeliveryZone $zone): bool
    {
        // Get the center point (the restaurant's location) from the zone's relationship.
        $centerLat = $zone->restaurant->latitude;
        $centerLng = $zone->restaurant->longitude;

        // The 'value' field directly holds the radius in meters.
        $radiusInMeters = $zone->value;

        // Earth's radius in meters.
        $earthRadius = 6371000;

        // Convert all latitude and longitude values from degrees to radians for the calculation.
        $latFrom = deg2rad($centerLat);
        $lonFrom = deg2rad($centerLng);
        $latTo = deg2rad($customerLat);
        $lonTo = deg2rad($customerLng);

        // Calculate the differences in coordinates.
        // The longitude difference is adjusted by the cosine of the average latitude
        // to compensate for the Earth's shape.
        $x = ($lonTo - $lonFrom) * cos(($latFrom + $latTo) / 2);
        $y = ($latTo - $latFrom);

        // Use the Pythagorean theorem to calculate the straight-line distance.
        $distance = sqrt($x * $x + $y * $y) * $earthRadius;

        // Return true if the calculated distance is within the specified radius.
        return $distance <= $radiusInMeters;
    }


    /**
     * Checks if a point is inside a polygon zone using the Ray-Casting algorithm.
     *
     * @param float $customerLat
     * @param float $customerLng
     * @param DeliveryZone $zone
     * @return bool
     */
    private function isInsidePolygon(float $customerLat, float $customerLng, DeliveryZone $zone): bool
    {
        $vertices = $zone->value;
        $intersections = 0;
        $verticesCount = count($vertices);

        for ($i = 0, $j = $verticesCount - 1; $i < $verticesCount; $j = $i++) {
            $vertex1 = $vertices[$i];
            $vertex2 = $vertices[$j];

            if (($vertex1['lng'] > $customerLng) != ($vertex2['lng'] > $customerLng) &&
                ($customerLat < ($vertex2['lat'] - $vertex1['lat']) * ($customerLng - $vertex1['lng']) / ($vertex2['lng'] - $vertex1['lng']) + $vertex1['lat'])
            ) {
                $intersections++;
            }
        }

        return ($intersections % 2) == 1;
    }


}
