<?php

namespace App\Policies\V1;

use App\Models\User;
use App\Models\V1\OrderAssignment;
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
        if (!$user->deliveryMan) {
            return false;
        }

        if ($orderAssignment->status !== 'pending') {
            return false;
        }

        return $user->deliveryMan->id === $orderAssignment->delivery_man_id;
    }
}
