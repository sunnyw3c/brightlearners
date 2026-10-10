<?php

use App\Domains\Access\Actions\GenerateSignedDownload;
use App\Domains\Access\Data\AccessDecision;
use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('GenerateSignedDownload returns short lived temporary URL for allowed access decision', function () {
    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => true]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true, 'file_path' => 'resources/test_file.pdf']);

    $user = User::factory()->create();
    $decision = AccessDecision::allow(AccessDecisionReason::Free, 'free');

    $action = app(GenerateSignedDownload::class);
    $url = $action->handle($decision, $resource, 'colour', $user);

    expect($url)->toBeString();
    expect($url)->not->toBeEmpty();
});

test('Signed download URL expires and is refused when accessed past lifetime', function () {
    Storage::disk('resources')->put('test_expire.pdf', 'dummy content');

    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => true]);
    ResourceVersion::factory()->create(['resource_id' => $resource->id, 'is_current' => true, 'file_path' => 'test_expire.pdf']);

    $user = User::factory()->create();
    $decision = AccessDecision::allow(AccessDecisionReason::Free, 'free');

    $action = app(GenerateSignedDownload::class);
    $url = $action->handle($decision, $resource, 'colour', $user);

    // Initial access is valid
    $this->get($url)->assertOk();

    // Travel past lifetime (10 minutes)
    $this->travel(10)->minutes();

    // Expired signature is refused with 403
    $this->get($url)->assertStatus(403);
});
