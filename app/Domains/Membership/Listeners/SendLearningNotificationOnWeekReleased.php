<?php

namespace App\Domains\Membership\Listeners;

use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Events\LearningWeekReleased;
use App\Domains\Membership\Models\Subscription;
use App\Notifications\WeeklyPackReleased;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLearningNotificationOnWeekReleased implements ShouldQueue
{
    public function handle(LearningWeekReleased $event): void
    {
        $week = $event->week;
        $week->loadMissing('program');
        $classId = $week->program?->class_id;

        if ($classId === null) {
            return;
        }

        $activeSubscriptions = Subscription::query()
            ->where('status', SubscriptionStatus::Active)
            ->where('ends_at', '>', now())
            ->with(['user.learningProfiles'])
            ->get();

        $notifiedUserIds = [];

        foreach ($activeSubscriptions as $subscription) {
            $user = $subscription->user;
            if ($user === null || in_array($user->id, $notifiedUserIds, true)) {
                continue;
            }

            $hasProfileInClass = $user->learningProfiles
                ->where('active', true)
                ->where('class_id', $classId)
                ->isNotEmpty();

            if ($hasProfileInClass) {
                $user->notify(new WeeklyPackReleased($week));
                $notifiedUserIds[] = $user->id;
            }
        }
    }
}
