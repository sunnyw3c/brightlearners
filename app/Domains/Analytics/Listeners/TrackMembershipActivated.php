<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Membership\Events\MembershipActivated;

class TrackMembershipActivated
{
    public function handle(MembershipActivated $event): void
    {
        $subscription = $event->subscription;

        Analytics::track(
            name: EventName::MembershipStarted,
            properties: [
                'subscription_id' => $subscription->id,
                'plan_id' => $subscription->plan_id,
                'paid_amount' => (int) ($subscription->paid_amount ?? 0),
            ],
            dedupeKey: 'sub_'.$subscription->id,
            userId: $subscription->user_id,
            source: 'server',
        );
    }
}
