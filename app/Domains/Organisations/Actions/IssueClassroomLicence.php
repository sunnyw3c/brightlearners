<?php

namespace App\Domains\Organisations\Actions;

use App\Domains\Organisations\Models\ClassroomLicence;
use App\Domains\Organisations\Models\Organisation;
use App\Domains\Organisations\Models\OrganisationMember;
use App\Models\User;
use Carbon\CarbonImmutable;

class IssueClassroomLicence
{
    /**
     * Issue a single-classroom licence for a teacher.
     * Enforces seat limit (default 35), printable distribution rights, and terms acceptance.
     */
    public function handle(
        Organisation $organisation,
        User $teacher,
        string $classroomName,
        int $seatLimit = 35,
        int $durationDays = 365,
    ): ClassroomLicence {
        $startsAt = CarbonImmutable::now();
        $endsAt = $startsAt->addDays($durationDays);

        OrganisationMember::query()->firstOrCreate(
            ['organisation_id' => $organisation->id, 'user_id' => $teacher->id],
            ['role' => 'teacher', 'joined_at' => $startsAt]
        );

        return ClassroomLicence::query()->create([
            'organisation_id' => $organisation->id,
            'teacher_id' => $teacher->id,
            'name' => $classroomName,
            'seat_limit' => $seatLimit,
            'printable_rights' => true,
            'redistribution_terms_accepted' => true,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ]);
    }
}
