<?php

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Downloading a resource logs the exact version and variant in downloads table', function () {
    $user = User::factory()->create();
    $resource = LearningResource::factory()->create(['status' => ResourceStatus::Published, 'is_free' => false]);
    $version1 = ResourceVersion::factory()->create(['resource_id' => $resource->id, 'version' => '1.0', 'is_current' => true, 'file_path' => 'resources/v1.pdf']);

    Entitlement::factory()->create(['user_id' => $user->id, 'resource_id' => $resource->id]);

    $this->actingAs($user)->get(route('downloads.show', ['resource' => $resource->slug, 'variant' => 'colour']))
        ->assertRedirect();

    $download = Download::where('user_id', $user->id)->first();
    expect($download)->not->toBeNull();
    expect($download->resource_version_id)->toBe($version1->id);
    expect($download->variant)->toBe('colour');
    expect($download->access_source)->toBe('purchase');

    // Update resource current version
    $version1->update(['is_current' => false]);
    $version2 = ResourceVersion::factory()->create(['resource_id' => $resource->id, 'version' => '2.0', 'is_current' => true, 'file_path' => 'resources/v2.pdf']);

    $this->actingAs($user)->get(route('downloads.show', ['resource' => $resource->slug, 'variant' => 'colour']))
        ->assertRedirect();

    expect(Download::where('user_id', $user->id)->count())->toBe(2);
    $latestDownload = Download::where('user_id', $user->id)->latest('id')->first();
    expect($latestDownload->resource_version_id)->toBe($version2->id);
});
