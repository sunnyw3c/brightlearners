<?php

namespace App\Domains\Membership\Actions;

use App\Domains\Membership\Enums\SubscriptionEventType;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionEvent;

class CancelRecurringSubscription
{
    /**
     * Cancel a recurring subscription adhering to customer terms:
     * Access remains active until `ends_at`, with auto-renewal disabled.
     */
    public function handle(
        Subscription $subscription,
        bool $immediately = false,
        ?string $reason = null,
    ): Subscription {
        $subscription->auto_renew = false;
        $subscription->cancelled_at = now();

        if ($immediately || $subscription->ends_at->isPast()) {
            $subscription->status = SubscriptionStatus::Cancelled;
            $subscription->cancel_at_period_end = false;
        } else {
            // Customer paid until ends_at, so retain access until then
            $subscription->cancel_at_period_end = true;
        }

        $subscription->save();

        SubscriptionEvent::query()->create([
            'subscription_id' => $subscription->id,
            'type' => SubscriptionEventType::Cancelled,
            'occurred_at' => now(),
            'metadata' => [
                'reason' => $reason ?? 'Cancelled by customer',
                'effective_until' => $subscription->ends_at->toIso8601String(),
                'immediately' => $immediately,
            ],
        ]);

        return $subscription;
    }
}
