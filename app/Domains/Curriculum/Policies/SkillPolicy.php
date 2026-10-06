<?php

namespace App\Domains\Curriculum\Policies;

use App\Domains\Curriculum\Models\Skill;
use App\Models\User;

class SkillPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('curriculum.view');
    }

    public function view(User $user, Skill $skill): bool
    {
        return $user->can('curriculum.view');
    }

    public function create(User $user): bool
    {
        return $user->can('curriculum.edit');
    }

    public function update(User $user, Skill $skill): bool
    {
        return $user->can('curriculum.edit');
    }

    public function delete(User $user, Skill $skill): bool
    {
        return $user->can('curriculum.edit') && ! $skill->classes()->exists();
    }

    public function restore(User $user, Skill $skill): bool
    {
        return $user->can('curriculum.edit');
    }

    public function forceDelete(User $user, Skill $skill): bool
    {
        return false;
    }
}
