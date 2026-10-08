<?php

namespace App\Console\Commands;

use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Events\OrderExpired;
use App\Domains\Commerce\Models\Order;
use Illuminate\Console\Command;

class ExpirePendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:expire-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel abandoned pending orders that have passed their expiration time.';

    public function handle(TransitionOrder $transitionOrder): int
    {
        $expiredOrders = Order::query()
            ->where('status', OrderStatus::PendingPayment)
            ->where('expires_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($expiredOrders as $order) {
            $transitionOrder->handle($order, OrderStatus::Cancelled);
            OrderExpired::dispatch($order);
            $count++;
        }

        $this->info("Expired and cancelled {$count} pending order(s).");

        return self::SUCCESS;
    }
}
