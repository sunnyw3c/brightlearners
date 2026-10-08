<?php

namespace App\Domains\Payments\Listeners;

use App\Domains\Payments\Events\OrderPaid;
use App\Notifications\OrderConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmationNotification implements ShouldQueue
{
    public function handle(OrderPaid $event): void
    {
        $order = $event->order;
        $user = $order->user;

        if ($user !== null) {
            $user->notify(new OrderConfirmation($order));
        }
    }
}
