<?php

namespace App\Domains\Membership\Listeners;

use App\Domains\Membership\Events\MembershipExpiring;
use App\Notifications\MembershipExpiringNotice;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMembershipExpiringNotice implements ShouldQueue
{
    public function handle(MembershipExpiring $event): void
    {
        $user = $event->subscription->user;

        if ($user !== null) {
            $user->notify(new MembershipExpiringNotice($event->subscription, $event->daysRemaining));
        }
    }
}
