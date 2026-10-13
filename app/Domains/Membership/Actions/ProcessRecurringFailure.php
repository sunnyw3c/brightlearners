<?php

namespace App\Domains\Membership\Actions;

use App\Domains\Membership\Enums\SubscriptionEventType;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionEvent;
use Carbon\CarbonImmutable;

class ProcessRecurringFailure
{
    /**
     * Handle recurring charge failure by setting a grace period and logging.
     */
    public function handle(
        Subscription $subscription,
        ?string $failureReason = null,
        int $graceDays = 5,
    ): Subscription {
        $graceEnd = CarbonImmutable::now()->addDays($graceDays);

        $subscription->grace_ends_at = $graceEnd;
        $subscription->save();

        SubscriptionEvent::query()->create([
            'subscription_id' => $subscription->id,
            'type' => SubscriptionEventType::RenewalFailed,
            'occurred_at' => now(),
            'metadata' => [
                'reason' => $failureReason ?? 'Recurring payment charge failed',
                'grace_ends_at' => $graceEnd->toIso8601String(),
            ],
        ]);

        return $subscription;
    }
}
