<?php

namespace App\Console\Commands;

use App\Domains\Membership\Enums\SubscriptionEventType;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Events\MembershipExpired;
use App\Domains\Membership\Events\MembershipExpiring;
use App\Domains\Membership\Models\Subscription;
use App\Domains\Membership\Models\SubscriptionEvent;
use Illuminate\Console\Command;

class ExpireMemberships extends Command
{
    protected $signature = 'membership:expire';

    protected $description = 'Expire memberships that have passed ends_at and send expiring notices';

    public function handle(): int
    {
        $now = now();

        // 1. Expire past memberships
        $expiredSubscriptions = Subscription::query()
            ->where('status', SubscriptionStatus::Active)
            ->where('ends_at', '<=', $now)
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => SubscriptionStatus::Expired]);

            SubscriptionEvent::query()->create([
                'subscription_id' => $subscription->id,
                'type' => SubscriptionEventType::Expired,
                'occurred_at' => $now,
            ]);

            MembershipExpired::dispatch($subscription);
        }

        $this->info("Expired {$expiredSubscriptions->count()} memberships.");

        // 2. Check for expiring notices (14 days and 3 days before expiry)
        $noticeDays = [14, 3];
        $noticesSent = 0;

        foreach ($noticeDays as $days) {
            $startWindow = $now->copy()->addDays($days)->startOfDay();
            $endWindow = $now->copy()->addDays($days)->endOfDay();

            $expiring = Subscription::query()
                ->where('status', SubscriptionStatus::Active)
                ->whereBetween('ends_at', [$startWindow, $endWindow])
                ->get();

            foreach ($expiring as $sub) {
                // Check if already dispatched notice for this interval
                $alreadySent = SubscriptionEvent::query()
                    ->where('subscription_id', $sub->id)
                    ->where('type', SubscriptionEventType::ExpiringNotice)
                    ->where('metadata->days_remaining', $days)
                    ->exists();

                if (! $alreadySent) {
                    SubscriptionEvent::query()->create([
                        'subscription_id' => $sub->id,
                        'type' => SubscriptionEventType::ExpiringNotice,
                        'occurred_at' => $now,
                        'metadata' => ['days_remaining' => $days],
                    ]);

                    MembershipExpiring::dispatch($sub, $days);
                    $noticesSent++;
                }
            }
        }

        $this->info("Dispatched {$noticesSent} expiring notices.");

        return self::SUCCESS;
    }
}
