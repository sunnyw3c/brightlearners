<?php

namespace App\Domains\Curriculum\Policies;

use App\Domains\Curriculum\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('curriculum.view');
    }

    public function view(User $user, Subject $subject): bool
    {
        return $user->can('curriculum.view');
    }

    public function create(User $user): bool
    {
        return $user->can('curriculum.edit');
    }

    public function update(User $user, Subject $subject): bool
    {
        return $user->can('curriculum.edit');
    }

    public function delete(User $user, Subject $subject): bool
    {
        return $user->can('curriculum.edit') && ! $subject->topics()->exists();
    }

    public function restore(User $user, Subject $subject): bool
    {
        return $user->can('curriculum.edit');
    }

    public function forceDelete(User $user, Subject $subject): bool
    {
        return false;
    }
}
