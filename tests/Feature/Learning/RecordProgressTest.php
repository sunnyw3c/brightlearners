<?php

use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Learning\Actions\RecordProgress;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

test('a progress update is denied without resource access', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id]);
    $paidResource = LearningResource::factory()->published()->create(['is_free' => false]);
    ResourceVersion::factory()->create(['resource_id' => $paidResource->id, 'is_current' => true]);

    $action = app(RecordProgress::class);

    expect(fn () => $action->handle($user, $profile, $paidResource, ProgressStatus::Opened))
        ->toThrow(AuthorizationException::class);
});

test('a progress update is denied if profile belongs to another user', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $profileB = LearningProfile::factory()->create(['user_id' => $userB->id]);
    $freeResource = LearningResource::factory()->published()->free()->create();
    ResourceVersion::factory()->create(['resource_id' => $freeResource->id, 'is_current' => true]);

    $action = app(RecordProgress::class);

    expect(fn () => $action->handle($userA, $profileB, $freeResource, ProgressStatus::Opened))
        ->toThrow(AuthorizationException::class);
});

test('status only moves forward and never moves backward', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id]);
    $resource = LearningResource::factory()->published()->free()->create();
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    $action = app(RecordProgress::class);

    // 1. Move to Opened
    $progress = $action->handle($user, $profile, $resource, ProgressStatus::Opened);
    expect($progress->status)->toBe(ProgressStatus::Opened)
        ->and($progress->opened_at)->not->toBeNull()
        ->and($progress->completed_at)->toBeNull();

    // 2. Try to move to NotStarted (should stay Opened)
    $progress = $action->handle($user, $profile, $resource, ProgressStatus::NotStarted);
    expect($progress->fresh()->status)->toBe(ProgressStatus::Opened);

    // 3. Move to Completed
    $progress = $action->handle($user, $profile, $resource, ProgressStatus::Completed);
    expect($progress->fresh()->status)->toBe(ProgressStatus::Completed)
        ->and($progress->completed_at)->not->toBeNull();

    // 4. Try to downgrade to Downloaded (should stay Completed)
    $progress = $action->handle($user, $profile, $resource, ProgressStatus::Downloaded);
    expect($progress->fresh()->status)->toBe(ProgressStatus::Completed);
});

test('parent can mark activity complete via account progress endpoint', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id]);
    $resource = LearningResource::factory()->published()->free()->create();
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    $response = $this->actingAs($user)->post(route('account.progress.store'), [
        'resource_id' => $resource->id,
        'profile_id' => $profile->id,
        'status' => 'completed',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('resource_progress', [
        'user_id' => $user->id,
        'learning_profile_id' => $profile->id,
        'resource_id' => $resource->id,
        'status' => ProgressStatus::Completed->value,
    ]);
});

test('opening resource in library records opened progress', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id]);
    $resource = LearningResource::factory()->published()->free()->create();
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    session(['active_learning_profile_id' => $profile->id]);

    $response = $this->actingAs($user)->get(route('account.library.show', $resource->slug));
    $response->assertOk();

    $this->assertDatabaseHas('resource_progress', [
        'user_id' => $user->id,
        'learning_profile_id' => $profile->id,
        'resource_id' => $resource->id,
        'status' => ProgressStatus::Opened->value,
    ]);
});

test('downloading resource records downloaded progress via listener', function () {
    $user = User::factory()->create();
    $profile = LearningProfile::factory()->create(['user_id' => $user->id]);
    $resource = LearningResource::factory()->published()->free()->create();
    $version = ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true]);

    session(['active_learning_profile_id' => $profile->id]);

    event(new ResourceDownloaded(
        resource: $resource,
        version: $version,
        userId: $user->id,
        accessSource: 'free',
    ));

    $this->assertDatabaseHas('resource_progress', [
        'user_id' => $user->id,
        'learning_profile_id' => $profile->id,
        'resource_id' => $resource->id,
        'status' => ProgressStatus::Downloaded->value,
    ]);
});
