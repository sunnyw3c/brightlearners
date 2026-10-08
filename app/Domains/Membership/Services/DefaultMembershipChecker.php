<?php

namespace App\Domains\Membership\Services;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Models\User;

class DefaultMembershipChecker implements MembershipChecker
{
    public function hasActiveMembership(?User $user): bool
    {
        return false;
    }

    public function canAccessResource(?User $user, LearningResource $resource): bool
    {
        return false;
    }
}
