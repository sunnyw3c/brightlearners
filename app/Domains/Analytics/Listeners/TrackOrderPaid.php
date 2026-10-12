<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Payments\Events\OrderPaid;

class TrackOrderPaid
{
    public function handle(OrderPaid $event): void
    {
        $order = $event->order;
        $order->loadMissing('items');

        $items = $order->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'price' => $item->unit_price,
            'quantity' => $item->quantity,
        ])->values()->all();

        Analytics::track(
            name: EventName::PurchaseCompleted,
            properties: [
                'order_id' => $order->id,
                'revenue' => $order->total,
                'items' => $items,
            ],
            dedupeKey: (string) $order->id,
            userId: $order->user_id,
            source: 'server',
        );
    }
}
