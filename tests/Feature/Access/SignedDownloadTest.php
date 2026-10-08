<?php

use App\Domains\Access\Actions\GenerateSignedDownload;
use App\Domains\Access\Data\AccessDecision;
use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
