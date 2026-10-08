<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Commerce\Actions\TransitionOrder;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Enums\RefundStatus;
use App\Domains\Payments\Enums\WebhookStatus;
use App\Domains\Payments\Events\RefundCompleted;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Models\Refund;
use App\Domains\Payments\Models\WebhookEvent;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessRazorpayWebhook
{
    public function __construct(
        private readonly MarkOrderPaid $markOrderPaid,
        private readonly TransitionOrder $transitionOrder,
    ) {}

    public function handle(WebhookEvent $event): void
    {
        if ($event->status === WebhookStatus::Processed) {
            return;
        }

        try {
            $payload = json_decode($event->payload, true) ?? [];
            $eventType = $event->event_type;

            match ($eventType) {
                'payment.captured', 'payment.authorized' => $this->handlePaymentSuccess($payload),
                'payment.failed' => $this->handlePaymentFailed($payload),
                'refund.processed' => $this->handleRefundProcessed($payload),
                default => Log::info("Unhandled Razorpay webhook event type: {$eventType}"),
            };

            $event->status = WebhookStatus::Processed;
            $event->processed_at = now();
            $event->save();
        } catch (Exception $e) {
            $event->status = WebhookStatus::Failed;
            $event->error = $e->getMessage();
            $event->attempts += 1;
            $event->save();

            throw $e;
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function handlePaymentSuccess(array $payload): void
    {
        $paymentEntity = $payload['payload']['payment']['entity'] ?? [];
        $razorpayOrderId = $paymentEntity['order_id'] ?? null;
        $razorpayPaymentId = $paymentEntity['id'] ?? null;
        $amount = (int) ($paymentEntity['amount'] ?? 0);
        $currency = $paymentEntity['currency'] ?? 'INR';
        $method = $paymentEntity['method'] ?? 'card';

        if (! $razorpayOrderId) {
            return;
        }

        $payment = Payment::query()->where('provider_order_id', $razorpayOrderId)->first();
        if ($payment === null) {
            return;
        }

        $order = $payment->order;
        if ($order === null) {
            return;
        }

        $this->markOrderPaid->handle(
            order: $order,
            payment: $payment,
            capturedAmount: $amount,
            currency: $currency,
            providerPaymentId: $razorpayPaymentId,
            method: $method,
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function handlePaymentFailed(array $payload): void
    {
        $paymentEntity = $payload['payload']['payment']['entity'] ?? [];
        $razorpayOrderId = $paymentEntity['order_id'] ?? null;
        $errorReason = $paymentEntity['error_description'] ?? 'Payment failed.';

        if (! $razorpayOrderId) {
            return;
        }

        $payment = Payment::query()->where('provider_order_id', $razorpayOrderId)->first();
        if ($payment === null) {
            return;
        }

        $payment->status = PaymentStatus::Failed;
        $payment->failure_reason = $errorReason;
        $payment->save();

        $order = $payment->order;
        if ($order !== null && $order->status === OrderStatus::PendingPayment) {
            $this->transitionOrder->handle($order, OrderStatus::Failed);
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function handleRefundProcessed(array $payload): void
    {
        $refundEntity = $payload['payload']['refund']['entity'] ?? [];
        $razorpayPaymentId = $refundEntity['payment_id'] ?? null;
        $razorpayRefundId = $refundEntity['id'] ?? null;
        $amount = (int) ($refundEntity['amount'] ?? 0);

        if (! $razorpayPaymentId) {
            return;
        }

        $payment = Payment::query()->where('provider_payment_id', $razorpayPaymentId)->first();
        if ($payment === null) {
            return;
        }

        DB::transaction(function () use ($payment, $razorpayRefundId, $amount) {
            $refund = Refund::query()->where('provider_refund_id', $razorpayRefundId)->first()
                ?? Refund::query()->where('payment_id', $payment->id)->where('status', RefundStatus::Pending)->first()
                ?? Refund::query()->create([
                    'payment_id' => $payment->id,
                    'amount' => $amount,
                    'status' => RefundStatus::Pending,
                    'provider_refund_id' => $razorpayRefundId,
                ]);

            $refund->status = RefundStatus::Processed;
            $refund->provider_refund_id = $razorpayRefundId;
            $refund->processed_at = now();
            $refund->save();

            $order = $payment->order;
            if ($order !== null) {
                $totalRefunded = $payment->fresh()->refundedAmount();
                $targetStatus = $totalRefunded >= $payment->amount
                    ? OrderStatus::Refunded
                    : OrderStatus::PartiallyRefunded;

                $this->transitionOrder->handle($order, $targetStatus);
            }

            DB::afterCommit(function () use ($refund) {
                RefundCompleted::dispatch($refund);
            });
        });
    }
}
