<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Exceptions\InvalidOrderStateTransitionException;
use App\Domains\Commerce\Models\Order;

class TransitionOrder
{
    /**
     * @var array<string, list<string>>
     */
    private const ALLOWED_TRANSITIONS = [
        OrderStatus::Draft->value => [
            OrderStatus::PendingPayment->value,
            OrderStatus::Cancelled->value,
        ],
        OrderStatus::PendingPayment->value => [
            OrderStatus::Paid->value,
            OrderStatus::Failed->value,
            OrderStatus::Cancelled->value,
        ],
        OrderStatus::Failed->value => [
            OrderStatus::PendingPayment->value,
            OrderStatus::Cancelled->value,
        ],
        OrderStatus::Paid->value => [
            OrderStatus::Refunded->value,
            OrderStatus::PartiallyRefunded->value,
        ],
        OrderStatus::PartiallyRefunded->value => [
            OrderStatus::Refunded->value,
        ],
        OrderStatus::Cancelled->value => [],
        OrderStatus::Refunded->value => [],
    ];

    public function handle(Order $order, OrderStatus $targetStatus): Order
    {
        $currentStatus = $order->status;

        if ($currentStatus === $targetStatus) {
            return $order;
        }

        $allowedNext = self::ALLOWED_TRANSITIONS[$currentStatus->value] ?? [];

        if (! in_array($targetStatus->value, $allowedNext, true)) {
            throw new InvalidOrderStateTransitionException($currentStatus, $targetStatus);
        }

        $order->status = $targetStatus;

        if ($targetStatus === OrderStatus::Paid) {
            $order->paid_at ??= now();
        }

        $order->save();

        // Release coupon usage if order becomes cancelled or failed
        if (in_array($targetStatus, [OrderStatus::Cancelled, OrderStatus::Failed], true)) {
            $order->couponUsage()?->delete();
        }

        return $order;
    }
}
