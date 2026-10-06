<?php

use App\Domains\Content\Actions\UploadResourceVersion;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('rejects a file renamed to .pdf whose real content is not a PDF', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);

    $notReallyAPdf = UploadedFile::fake()->create('worksheet.pdf', 10, 'text/plain');

    expect(fn () => app(UploadResourceVersion::class)->handle($resource, $notReallyAPdf, $uploader))
        ->toThrow(InvalidArgumentException::class);

    Storage::disk('resources')->assertDirectoryEmpty((string) $resource->id);
});

test('accepts a real PDF file', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);

    $pdf = UploadedFile::fake()->create('worksheet.pdf', 10, 'application/pdf');

    $version = app(UploadResourceVersion::class)->handle($resource, $pdf, $uploader);

    Storage::disk('resources')->assertExists($version->file_path);
});
