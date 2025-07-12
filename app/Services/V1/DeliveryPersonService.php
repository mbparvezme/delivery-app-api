<?php

namespace App\Services\V1;

use App\Models\V1\DeliveryMan;
use App\Models\V1\User;

class DeliveryPersonService
{
    /**
     * Updates the status and location for a delivery person.
     *
     * @param User $user The authenticated user who is a delivery person.
     * @param array $data The validated data from the request.
     * @return DeliveryMan The updated delivery person model.
     */
    public function updateStatus(User $user, array $data): DeliveryMan
    {
        $deliveryMan = $user->deliveryMan()->firstOrFail();

        if (isset($data['current_latitude'])) {
            $data['location_updated_at'] = now();
        }

        $deliveryMan->update($data);
        return $deliveryMan;
    }
}
