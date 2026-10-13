<?php

namespace App\Domains\Membership\Actions;

use App\Domains\Membership\Enums\SubscriptionEventType;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionEvent;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ProcessRecurringRenewal
{
    /**
     * Process recurring renewal payment for a subscription.
     * Guaranteed idempotent: processing the same payment/event ID twice
     * extends the subscription term only once.
     */
    public function handle(
        Subscription $subscription,
        string $providerPaymentId,
        int $amount,
        string $currency = 'INR',
        ?string $renewalReference = null,
    ): Subscription {
        return DB::transaction(function () use (
            $subscription,
            $providerPaymentId,
            $amount,
            $currency,
            $renewalReference,
        ) {
            $reference = $renewalReference ?? $providerPaymentId;

            // Idempotency guard: if a renewal payment with this reference already exists, do nothing
            $existingPayment = Payment::query()
                ->where('subscription_id', $subscription->id)
                ->where('renewal_reference', $reference)
                ->first();

            if ($existingPayment !== null) {
                return $subscription;
            }

            $plan = $subscription->plan;
            $currentEnd = CarbonImmutable::parse($subscription->ends_at);

            // If subscription was expired in the past, renew from now; otherwise extend from current end
            $baseDate = $currentEnd->isFuture() ? $currentEnd : CarbonImmutable::now();

            $newEnd = $plan && $plan->duration_days
                ? $baseDate->addDays($plan->duration_days)
                : $baseDate->addMonths($plan?->duration_months ?? 1);

            $subscription->current_term_starts_at = $baseDate;
            $subscription->current_term_ends_at = $newEnd;
            $subscription->ends_at = $newEnd;
            $subscription->status = SubscriptionStatus::Active;
            $subscription->grace_ends_at = null; // Clear any pending grace period
            $subscription->save();

            // Record renewal payment
            Payment::query()->create([
                'order_id' => $subscription->source_order_id,
                'subscription_id' => $subscription->id,
                'provider' => $subscription->provider ?? 'razorpay',
                'provider_order_id' => 'renewal-sub-'.$subscription->id.'-'.time(),
                'provider_payment_id' => $providerPaymentId,
                'amount' => $amount,
                'currency' => $currency,
                'status' => PaymentStatus::Captured,
                'method' => 'recurring',
                'verified_at' => now(),
                'paid_at' => now(),
                'is_renewal' => true,
                'renewal_reference' => $reference,
            ]);

            SubscriptionEvent::query()->create([
                'subscription_id' => $subscription->id,
                'type' => SubscriptionEventType::Renewed,
                'occurred_at' => now(),
                'metadata' => [
                    'provider_payment_id' => $providerPaymentId,
                    'renewal_reference' => $reference,
                    'amount' => $amount,
                    'new_ends_at' => $newEnd->toIso8601String(),
                ],
            ]);

            return $subscription;
        });
    }
}
