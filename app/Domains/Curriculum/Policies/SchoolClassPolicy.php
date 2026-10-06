<?php

namespace App\Domains\Curriculum\Policies;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Models\User;

class SchoolClassPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('curriculum.view');
    }

    public function view(User $user, SchoolClass $schoolClass): bool
    {
        return $user->can('curriculum.view');
    }

    public function create(User $user): bool
    {
        return $user->can('curriculum.edit');
    }

    public function update(User $user, SchoolClass $schoolClass): bool
    {
        return $user->can('curriculum.edit');
    }

    /**
     * There is no delete button for a row that has content: archive it
     * instead (`active = false`).
     */
    public function delete(User $user, SchoolClass $schoolClass): bool
    {
        return $user->can('curriculum.edit') && ! $this->isReferenced($schoolClass);
    }

    public function restore(User $user, SchoolClass $schoolClass): bool
    {
        return $user->can('curriculum.edit');
    }

    public function forceDelete(User $user, SchoolClass $schoolClass): bool
    {
        return false;
    }

    private function isReferenced(SchoolClass $schoolClass): bool
    {
        return $schoolClass->subjects()->exists()
            || $schoolClass->skills()->exists()
            || $schoolClass->learningProfiles()->exists();
    }
}
