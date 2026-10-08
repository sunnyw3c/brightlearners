<?php

namespace App\Domains\Membership\Contracts;

use App\Domains\Content\Models\LearningResource;
use App\Models\User;

interface MembershipChecker
{
    public function hasActiveMembership(?User $user): bool;

    public function canAccessResource(?User $user, LearningResource $resource): bool;
}
