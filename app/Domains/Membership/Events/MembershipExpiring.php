<?php

namespace App\Domains\Membership\Events;

use App\Domains\Membership\Models\Subscription;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipExpiring
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Subscription $subscription,
        public readonly int $daysRemaining,
    ) {}
}
