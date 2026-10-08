<?php

namespace App\Console\Commands;

use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Actions\MarkOrderPaid;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Throwable;

class ReconcilePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:reconcile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile pending payments with Razorpay API';

    public function handle(MarkOrderPaid $markOrderPaid, TransitionOrder $transitionOrder): int
    {
        $pendingOrders = Order::query()
            ->where('status', OrderStatus::PendingPayment)
            ->where('created_at', '<=', now()->subMinutes(5))
            ->get();

        $reconciledCount = 0;

        foreach ($pendingOrders as $order) {
            $payment = Payment::query()
                ->where('order_id', $order->id)
                ->where('provider', 'razorpay')
                ->whereIn('status', [PaymentStatus::Created, PaymentStatus::Authorized])
                ->first();

            if ($payment === null) {
                continue;
            }

            $keyId = config('services.razorpay.key_id');
            $keySecret = config('services.razorpay.key_secret');

            if (! $keyId || ! $keySecret || ! class_exists(Api::class) || Str::contains($keyId, 'rzp_test_key_id')) {
                continue;
            }

            try {
                $api = new Api($keyId, $keySecret);
                $rzpOrder = $api->order->fetch($payment->provider_order_id);
                $rzpPayments = $rzpOrder->payments();

                $capturedPayment = null;
                $failedPayment = null;

                foreach ($rzpPayments->items as $item) {
                    if (($item['status'] ?? null) === 'captured') {
                        $capturedPayment = $item;
                        break;
                    }
                    if (($item['status'] ?? null) === 'failed') {
                        $failedPayment = $item;
                    }
                }

                if ($capturedPayment !== null) {
                    $markOrderPaid->handle(
                        order: $order,
                        payment: $payment,
                        capturedAmount: (int) $capturedPayment['amount'],
                        currency: $capturedPayment['currency'] ?? $order->currency,
                        providerPaymentId: $capturedPayment['id'],
                        method: $capturedPayment['method'] ?? 'card',
                    );
                    $reconciledCount++;
                    $this->info("Reconciled order #{$order->order_number} as PAID.");
                } elseif ($failedPayment !== null) {
                    $payment->status = PaymentStatus::Failed;
                    $payment->failure_reason = $failedPayment['error_description'] ?? 'Payment failed on Razorpay.';
                    $payment->save();

                    $transitionOrder->handle($order, OrderStatus::Failed);
                    $reconciledCount++;
                    $this->info("Reconciled order #{$order->order_number} as FAILED.");
                }
            } catch (Throwable $e) {
                $this->error("Failed to reconcile order #{$order->order_number}: {$e->getMessage()}");
            }
        }

        $this->info("Payment reconciliation finished. Reconciled {$reconciledCount} orders.");

        return Command::SUCCESS;
    }
}
