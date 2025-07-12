<?php

namespace App\Policies\V1;

use App\Models\V1\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     * A user can view an order if they are the one who created it.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can cancel the model.
     * A user can cancel an order if they own it AND its status is 'pending'.
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->status === 'pending';
    }

}
