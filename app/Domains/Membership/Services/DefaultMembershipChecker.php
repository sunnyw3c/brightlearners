<?php

namespace App\Domains\Membership\Services;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Models\LearningItem;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;

class DefaultMembershipChecker implements MembershipChecker
{
    public function hasActiveMembership(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        $now = now();

        return Subscription::query()
            ->where('user_id', $user->id)
            ->where('status', SubscriptionStatus::Active)
            ->where('starts_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->where('ends_at', '>', $now)
                    ->orWhere(function ($gq) use ($now) {
                        $gq->whereNotNull('grace_ends_at')
                            ->where('grace_ends_at', '>', $now);
                    });
            })
            ->exists();
    }

    public function canAccessResource(?User $user, LearningResource $resource): bool
    {
        if ($user === null) {
            return false;
        }

        $now = now();
        $allowRedownloadAfterExpiry = (bool) config('membership.redownload_after_expiry', false);

        // Fetch subscriptions
        $subscriptionsQuery = Subscription::query()->where('user_id', $user->id);
        if (! $allowRedownloadAfterExpiry) {
            $subscriptionsQuery
                ->where('status', SubscriptionStatus::Active)
                ->where('starts_at', '<=', $now)
                ->where(function ($q) use ($now) {
                    $q->where('ends_at', '>', $now)
                        ->orWhere(function ($gq) use ($now) {
                            $gq->whereNotNull('grace_ends_at')
                                ->where('grace_ends_at', '>', $now);
                        });
                });
        } else {
            $subscriptionsQuery->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired]);
        }

        $subscriptions = $subscriptionsQuery->get();
        if ($subscriptions->isEmpty()) {
            return false;
        }

        // Active profile class IDs
        $userClassIds = $user->learningProfiles()
            ->where('active', true)
            ->whereNotNull('class_id')
            ->pluck('class_id')
            ->all();

        if (empty($userClassIds)) {
            return false;
        }

        // 1. Resource must be an item in a released week
        $learningItems = LearningItem::query()
            ->where('resource_id', $resource->id)
            ->with(['week.program'])
            ->get();

        foreach ($learningItems as $item) {
            $week = $item->week;
            if ($week === null || $week->released_at === null || $week->released_at->isFuture()) {
                continue;
            }

            $program = $week->program;
            if ($program === null || $program->status !== LearningProgramStatus::Published) {
                continue;
            }

            // 2. That week's program must be for a class of one of the user's active learning profiles
            if (! in_array($program->class_id, $userClassIds, true)) {
                continue;
            }

            // 3 & 4. Covered by an eligible subscription
            foreach ($subscriptions as $sub) {
                $releasedDuring = $week->released_at->greaterThanOrEqualTo($sub->starts_at)
                    && $week->released_at->lessThanOrEqualTo($sub->ends_at);

                $programMonthCurrent = $program->month === (int) $sub->starts_at->format('n')
                    && $program->year === (int) $sub->starts_at->format('Y');

                if ($releasedDuring || $programMonthCurrent) {
                    return true;
                }
            }
        }

        return false;
    }
}
