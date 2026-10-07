<?php

namespace App\Console\Commands;

use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Events\OrderExpired;
use App\Domains\Commerce\Models\Order;
use Illuminate\Console\Command;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire-pending';

    protected $description = 'Cancel expired pending orders and release their coupon usage';

    public function handle(TransitionOrder $transitionOrder): int
    {
        $expired = 0;

        Order::query()
            ->where('status', OrderStatus::PendingPayment->value)
            ->where('expires_at', '<=', now())
            ->orderBy('id')
            ->chunkById(100, function ($orders) use ($transitionOrder, &$expired): void {
                foreach ($orders as $order) {
                    $cancelled = $transitionOrder->handle($order, OrderStatus::Cancelled);
                    OrderExpired::dispatch($cancelled);
                    $expired++;
                }
            });

        $this->info("Expired {$expired} pending order(s).");

        return self::SUCCESS;
    }
}
