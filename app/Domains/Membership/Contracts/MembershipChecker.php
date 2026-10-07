<?php

namespace App\Domains\Membership\Contracts;

use App\Models\User;

interface MembershipChecker
{
    public function hasActiveMembership(?User $user): bool;
}
