<?php

namespace App\Domains\Commerce\Policies;

use App\Domains\Commerce\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('orders.view');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->can('orders.view');
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('orders.manage');
    }

    public function delete(User $user, Order $order): bool
    {
        return false;
    }
}
