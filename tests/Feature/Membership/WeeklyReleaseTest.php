<?php

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Membership\Actions\ReleaseLearningWeek;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Events\LearningWeekReleased;
use App\Domains\Membership\Models\LearningItem;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('A week with an unpublished resource or unpublished program is not released', function () {
    $program = LearningProgram::factory()->draft()->create();
    $week = LearningWeek::factory()->dueForRelease()->create([
        'program_id' => $program->id,
        'week_number' => 1,
    ]);

    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Draft,
    ]);

    LearningItem::factory()->create([
        'week_id' => $week->id,
        'resource_id' => $resource->id,
    ]);

    $action = app(ReleaseLearningWeek::class);
    $result = $action->handle($week);

    expect($result)->toBeFalse();
    expect($week->fresh()->released_at)->toBeNull();
});

test('A due week with published program and published resources is released and fires event', function () {
    Event::fake([LearningWeekReleased::class]);

    $program = LearningProgram::factory()->published()->create();
    $week = LearningWeek::factory()->dueForRelease()->create([
        'program_id' => $program->id,
        'week_number' => 1,
    ]);

    $resource = LearningResource::factory()->create([
        'status' => ResourceStatus::Published,
    ]);
    ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'is_current' => true,
    ]);

    LearningItem::factory()->create([
        'week_id' => $week->id,
        'resource_id' => $resource->id,
    ]);

    $this->artisan('membership:release-weeks')->assertSuccessful();

    $week->refresh();
    expect($week->released_at)->not->toBeNull();
    expect($week->isReleased())->toBeTrue();

    Event::assertDispatched(LearningWeekReleased::class);
});

test('Step 10.10: Week 4 blocks release if next-month readiness is required and missing', function () {
    config(['membership.require_next_month_readiness' => true]);

    $program = LearningProgram::factory()->published()->create([
        'month' => 5,
        'year' => 2026,
    ]);
    $week4 = LearningWeek::factory()->dueForRelease()->create([
        'program_id' => $program->id,
        'week_number' => 4,
    ]);

    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Published]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);
    LearningItem::factory()->create(['week_id' => $week4->id, 'resource_id' => $resource->id]);

    $action = app(ReleaseLearningWeek::class);
    // Month 6 doesn't exist yet -> blocked
    expect($action->handle($week4))->toBeFalse();
    expect($week4->fresh()->released_at)->toBeNull();

    // Now create Month 6 as Ready
    LearningProgram::factory()->create([
        'class_id' => $program->class_id,
        'month' => 6,
        'year' => 2026,
        'status' => LearningProgramStatus::Ready,
    ]);

    // Now Week 4 can be released
    expect($action->handle($week4))->toBeTrue();
    expect($week4->fresh()->released_at)->not->toBeNull();
});
