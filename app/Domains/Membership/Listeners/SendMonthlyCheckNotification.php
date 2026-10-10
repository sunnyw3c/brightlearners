<?php

namespace App\Domains\Membership\Listeners;

use App\Domains\Membership\Enums\SubscriptionStatus;
use App\Domains\Membership\Events\LearningMonthPublished;
use App\Domains\Membership\Models\Subscription;
use App\Notifications\MonthlyCheckAvailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMonthlyCheckNotification implements ShouldQueue
{
    public function handle(LearningMonthPublished $event): void
    {
        $program = $event->program;
        $classId = $program->class_id;

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
                $user->notify(new MonthlyCheckAvailable($program));
                $notifiedUserIds[] = $user->id;
            }
        }
    }
}
