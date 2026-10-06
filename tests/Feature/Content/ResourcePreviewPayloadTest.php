<?php

use App\Domains\Content\Data\ResourcePreviewPayload;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Support\Facades\Storage;

test('it exposes preview image URLs and metadata, never a file path', function () {
    Storage::fake('previews');

    $resource = LearningResource::factory()->create();
    $version = ResourceVersion::factory()->published()->create([
        'resource_id' => $resource->id,
        'file_path' => 'secret/path/to/file.pdf',
    ]);
    $preview = ResourcePreview::factory()->create(['resource_version_id' => $version->id, 'sort_order' => 1]);

    $payload = ResourcePreviewPayload::fromResource($resource->refresh());

    expect($payload->title)->toBe($resource->title);
    expect($payload->previewImageUrls)->toHaveCount(1);
    expect($payload->previewImageUrls[0])->toContain($preview->image_path);
    expect(json_encode($payload))->not->toContain('secret/path/to/file.pdf');
});

test('it returns no preview images when the resource has no published version', function () {
    $resource = LearningResource::factory()->create();

    $payload = ResourcePreviewPayload::fromResource($resource);

    expect($payload->previewImageUrls)->toBe([]);
});
