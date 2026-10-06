<?php

namespace App\Domains\Accounts\Policies;

use App\Domains\Accounts\Models\LearningProfile;
use App\Models\User;

class LearningProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LearningProfile $profile): bool
    {
        return $profile->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, LearningProfile $profile): bool
    {
        return $profile->user_id === $user->id;
    }

    public function delete(User $user, LearningProfile $profile): bool
    {
        return $profile->user_id === $user->id;
    }
}
