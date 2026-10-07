<?php

namespace App\Domains\Membership\Services;

use App\Domains\Membership\Contracts\MembershipChecker;
use App\Models\User;

class NullMembershipChecker implements MembershipChecker
{
    public function hasActiveMembership(?User $user): bool
    {
        return false;
    }
}
