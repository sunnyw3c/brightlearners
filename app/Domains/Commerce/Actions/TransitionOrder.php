<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use DomainException;
use Illuminate\Support\Facades\DB;

class TransitionOrder
{
    public function handle(Order $order, OrderStatus $next): Order
    {
        return DB::transaction(function () use ($order, $next): Order {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! $locked->status->canTransitionTo($next)) {
                throw new DomainException("Order cannot move from {$locked->status->value} to {$next->value}.");
            }

            $locked->applyStatus($next);

            if ($next === OrderStatus::Paid) {
                $locked->forceFill(['paid_at' => now(), 'expires_at' => null])->save();
            }

            if (in_array($next, [OrderStatus::Failed, OrderStatus::Cancelled], true)) {
                $locked->couponUsage()->delete();
            }

            return $locked->refresh();
        });
    }
}
