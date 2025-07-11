<?php

namespace App\Services\V1;

use App\Models\V1\Restaurant;
use App\Models\V1\DeliveryZone;

class DeliveryZoneService
{
    /**
     * Create a new delivery zone for a specific restaurant.
     *
     * @param Restaurant $restaurant The restaurant to which the zone belongs.
     * @param array $data The validated data for the new zone.
     * @return DeliveryZone
     */
    public function createZone(Restaurant $restaurant, array $data): DeliveryZone
    {
        // The 'coordinates' field is already a PHP array from the request.
        // We no longer need to decode it. Eloquent will handle encoding it
        // back to a JSON string when saving to the database.

        // Use the relationship to create the new zone.
        $deliveryZone = $restaurant->deliveryZones()->create($data);

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
}
