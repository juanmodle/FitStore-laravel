<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('orders.view-own') || $user->can('orders.view-all');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('orders.view-all')
            || ($user->can('orders.view-own') && $user->id === $order->user_id);
    }
}
