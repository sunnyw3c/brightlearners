<?php

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Actions\SendCorrectionNoticesAction;
use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use App\Notifications\CorrectionNotice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('a correction notice goes to the right people once and a second send is refused', function () {
    Notification::fake();

    $resource = LearningResource::factory()->create();
    $unrelatedResource = LearningResource::factory()->create();

    $oldVersion = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '1.0',
    ]);

    $newVersion = ResourceVersion::factory()->create([
        'resource_id' => $resource->id,
        'version' => '1.1',
    ]);

    // User A: downloaded the old version
    $userA = User::factory()->create();
    Download::factory()->create([
        'user_id' => $userA->id,
        'resource_id' => $resource->id,
        'resource_version_id' => $oldVersion->id,
    ]);

    // User B: actively entitled to the resource
    $userB = User::factory()->create();
    Entitlement::factory()->create([
        'user_id' => $userB->id,
        'resource_id' => $resource->id,
        'revoked_at' => null,
    ]);

    // User C: only downloaded unrelated resource
    $userC = User::factory()->create();
    Download::factory()->create([
        'user_id' => $userC->id,
        'resource_id' => $unrelatedResource->id,
    ]);

    $correction = ResourceCorrection::query()->create([
        'resource_id' => $resource->id,
        'version_from' => '1.0',
        'version_to' => '1.1',
        'severity' => CorrectionSeverity::Material,
        'customer_notice_required' => true,
        'notes' => 'Corrected answer on question 4',
        'notified_at' => null,
        'created_by' => $userA->id,
    ]);

    $action = app(SendCorrectionNoticesAction::class);

    // First send: succeeds
    $recipients = $action->handle($correction);

    expect($recipients->pluck('id'))->toContain($userA->id, $userB->id);
    expect($recipients->pluck('id'))->not->toContain($userC->id);

    Notification::assertSentTo([$userA, $userB], CorrectionNotice::class);
    Notification::assertNotSentTo([$userC], CorrectionNotice::class);

    expect($correction->fresh()->notified_at)->not->toBeNull();

    // Second send: refused
    expect(fn () => $action->handle($correction->fresh()))
        ->toThrow(ValidationException::class);
});
