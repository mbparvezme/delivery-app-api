<?php

namespace App\Policies\V1;

use App\Models\V1\Restaurant;
use App\Models\User;

class RestaurantPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }


    /**
     * Determine whether the user can assign orders for the restaurant.
     *
     * @param  \App\Models\V1\User  $user
     * @param  \App\Models\V1\Restaurant  $restaurant
     * @return bool
     */
    public function assign(User $user, Restaurant $restaurant): bool
    {
        // Only the user who owns the restaurant can assign orders for it.
        return $user->id === $restaurant->user_id;
    }
}
