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

    /**
     * Determine whether the user can track the delivery of the order.
     *
     * @param  \App\Models\V1\User  $user
     * @param  \App\Models\V1\Order  $order
     * @return bool
     */
    public function trackDelivery(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->id === $order->restaurant->user_id;
    }

}
