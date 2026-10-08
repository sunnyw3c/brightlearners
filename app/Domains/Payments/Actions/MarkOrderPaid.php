<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Events\OrderPaid;
use App\Domains\Payments\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class MarkOrderPaid
{
    public function __construct(
        private readonly TransitionOrder $transitionOrder,
    ) {}

    public function handle(Order $order, Payment $payment, int $capturedAmount, string $currency = 'INR', ?string $providerPaymentId = null, ?string $method = null): Order
    {
        // 1. Check if amount or currency differs from the order before transaction so payment failure persists
        if ($capturedAmount !== $order->total || strtoupper($currency) !== strtoupper($order->currency)) {
            Log::warning("Payment amount/currency mismatch for Order {$order->order_number}. Order expected {$order->total} {$order->currency}, received {$capturedAmount} {$currency}.");

            $payment->status = PaymentStatus::Failed;
            $payment->failure_reason = 'Amount or currency mismatch during verification.';
            $payment->save();

            throw new InvalidArgumentException("Captured payment amount ({$capturedAmount}) does not match order total ({$order->total}).");
        }

        return DB::transaction(function () use ($order, $payment, $capturedAmount, $currency, $providerPaymentId, $method): Order {
            // Lock the order row to prevent concurrent mark-paid executions
            $order = Order::query()->where('id', $order->id)->lockForUpdate()->firstOrFail();

            // 2. Return quietly if order is already paid (idempotency guard)
            if ($order->status === OrderStatus::Paid) {
                return $order;
            }

            // 3. Move order to paid, update payment record
            $this->transitionOrder->handle($order, OrderStatus::Paid);

            $payment->status = PaymentStatus::Captured;
            $payment->amount = $capturedAmount;
            $payment->currency = strtoupper($currency);
            if ($providerPaymentId) {
                $payment->provider_payment_id = $providerPaymentId;
            }
            if ($method) {
                $payment->method = $method;
            }
            $payment->verified_at ??= now();
            $payment->paid_at ??= now();
            $payment->save();

            // 4. Dispatch OrderPaid event after transaction commits
            DB::afterCommit(function () use ($order, $payment) {
                OrderPaid::dispatch($order, $payment);
            });

            return $order;
        });
    }
}
