<?php

namespace App\Domains\Organisations\Services;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Organisations\Models\ClassroomLicence;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ClassroomAccessService
{
    /**
     * Check if a teacher has active classroom licensing.
     */
    public function hasActiveLicence(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return ClassroomLicence::query()
            ->where('teacher_id', $user->id)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->exists();
    }

    /**
     * Get active classroom licences for a teacher.
     *
     * @return Collection<int, ClassroomLicence>
     */
    public function getActiveLicences(User $user)
    {
        return ClassroomLicence::query()
            ->where('teacher_id', $user->id)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->get();
    }

    /**
     * Check if a user has active classroom licence access.
     */
    public function hasAccess(?User $user, ?LearningResource $resource = null): bool
    {
        return $this->hasActiveLicence($user);
    }

    /**
     * Check if a teacher has printable distribution rights under their classroom licence.
     */
    public function canPrintForClassroom(?User $user, ?LearningResource $resource = null): bool
    {
        if ($user === null) {
            return false;
        }

        return ClassroomLicence::query()
            ->where('teacher_id', $user->id)
            ->where('status', 'active')
            ->where('printable_rights', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->exists();
    }

    /**
     * Get active maximum classroom seat limit for a teacher.
     */
    public function getActiveSeatLimit(?User $user): int
    {
        if ($user === null) {
            return 0;
        }

        return (int) (ClassroomLicence::query()
            ->where('teacher_id', $user->id)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->max('seat_limit') ?? 0);
    }
}
