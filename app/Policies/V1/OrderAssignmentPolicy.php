<?php

namespace App\Policies\V1;

use App\Models\V1\OrderAssignment;
use App\Models\V1\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderAssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can respond to the assignment (accept or reject).
     *
     * @param  \App\Models\V1\User  $user
     * @param  \App\Models\V1\OrderAssignment  $orderAssignment
     * @return bool
     */
    public function respond(User $user, OrderAssignment $orderAssignment): bool
    {
        // 1. Check if the authenticated user has a delivery person profile.
        if (!$user->deliveryMan) {
            return false;
        }

        // 2. Check if the assignment is still in a 'pending' state.
        if ($orderAssignment->status !== 'pending') {
            return false;
        }

        // 3. Check if the assignment actually belongs to this delivery person.
        return $user->deliveryMan->id === $orderAssignment->delivery_man_id;
    }
}
