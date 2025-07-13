<?php

namespace App\Policies\V1;

use App\Models\User;
use App\Models\V1\DeliveryZone;
use App\Models\V1\Restaurant;

class DeliveryZonePolicy
{

    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyZone(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewZone(User $user, Restaurant $restaurant, DeliveryZone $zone): bool
    {
        return $user->id === $restaurant->user_id && $zone->restaurant_id === $restaurant->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function createZone(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateZone(User $user, Restaurant $restaurant, DeliveryZone $zone): bool
    {
        return $user->id === $restaurant->user_id && $zone->restaurant_id === $restaurant->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteZone(User $user, Restaurant $restaurant, DeliveryZone $zone): bool
    {
        return $user->id === $restaurant->user_id && $zone->restaurant_id === $restaurant->id;
    }
}
