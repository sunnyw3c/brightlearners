<?php

use App\Domains\Content\Models\LearningResource;
use App\Domains\Organisations\Actions\CreateOrganisation;
use App\Domains\Organisations\Actions\IssueClassroomLicence;
use App\Domains\Organisations\Models\ClassroomLicence;
use App\Domains\Organisations\Models\Organisation;
use App\Domains\Organisations\Services\ClassroomAccessService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('organization owner can create organisation account', function () {
    $owner = User::factory()->create();

    $action = app(CreateOrganisation::class);
    $organisation = $action->handle($owner, 'St. Jude Academy', 'school');

    expect($organisation->id)->not->toBeNull()
        ->and($organisation->name)->toBe('St. Jude Academy')
        ->and($organisation->owner_id)->toBe($owner->id)
        ->and($organisation->members()->where('user_id', $owner->id)->where('role', 'admin')->exists())->toBeTrue();
});

test('issue classroom licence sets 35 seat limit and printable distribution rights', function () {
    $owner = User::factory()->create();
    $teacher = User::factory()->create();
    $organisation = Organisation::factory()->create(['owner_id' => $owner->id]);

    $action = app(IssueClassroomLicence::class);
    $licence = $action->handle($organisation, $teacher, 'Class 3-B Mathematics', 35);

    expect($licence->seat_limit)->toBe(35)
        ->and($licence->printable_rights)->toBeTrue()
        ->and($licence->redistribution_terms_accepted)->toBeTrue()
        ->and($licence->status)->toBe('active')
        ->and($licence->isActive())->toBeTrue()
        ->and($organisation->members()->where('user_id', $teacher->id)->where('role', 'teacher')->exists())->toBeTrue();
});

test('classroom access service grants access to licensed teacher with active licence', function () {
    $organisation = Organisation::factory()->create();
    $teacher = User::factory()->create();
    $resource = LearningResource::factory()->published()->create(['is_free' => false]);

    ClassroomLicence::factory()->create([
        'organisation_id' => $organisation->id,
        'teacher_id' => $teacher->id,
        'status' => 'active',
        'seat_limit' => 35,
        'printable_rights' => true,
    ]);

    $service = app(ClassroomAccessService::class);

    expect($service->hasAccess($teacher, $resource))->toBeTrue()
        ->and($service->canPrintForClassroom($teacher, $resource))->toBeTrue()
        ->and($service->getActiveSeatLimit($teacher))->toBe(35);
});

test('classroom access service denies access when licence is expired or non-member', function () {
    $organisation = Organisation::factory()->create();
    $teacher = User::factory()->create();
    $unrelatedUser = User::factory()->create();
    $resource = LearningResource::factory()->published()->create(['is_free' => false]);

    ClassroomLicence::factory()->expired()->create([
        'organisation_id' => $organisation->id,
        'teacher_id' => $teacher->id,
    ]);

    $service = app(ClassroomAccessService::class);

    expect($service->hasAccess($teacher, $resource))->toBeFalse()
        ->and($service->canPrintForClassroom($teacher, $resource))->toBeFalse()
        ->and($service->hasAccess($unrelatedUser, $resource))->toBeFalse();
});
