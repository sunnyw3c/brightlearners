<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Commerce\Events\CartConvertedToOrder;

class TrackCartConvertedToOrder
{
    public function handle(CartConvertedToOrder $event): void
    {
        $order = $event->order;

        Analytics::track(
            name: EventName::CheckoutStarted,
            properties: [
                'order_id' => $order->id,
                'total' => $order->total,
                'item_count' => $order->items()->count(),
            ],
            userId: $order->user_id,
            source: 'server',
        );
    }
}
