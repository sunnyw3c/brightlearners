<?php

use App\Domains\Content\Actions\UploadResourceVersion;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * These tests write to the real `resources` and `previews` disks (both
 * local in this environment) instead of Storage::fake(), because what is
 * under test is precisely the thing Storage::fake() replaces: the real
 * disk's visibility default and Laravel's signed-URL serving route
 * (`storage.resources`, registered because config/filesystems.php sets
 * `serve => true` with no `visibility`). Each test cleans up the file it
 * writes.
 */
afterEach(function () {
    Storage::disk('resources')->deleteDirectory('test-private-file');
});

test('a resource version file is stored on the private resources disk, not a public one', function () {
    $uploader = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);

    $version = app(UploadResourceVersion::class)->handle(
        $resource,
        UploadedFile::fake()->create('worksheet.pdf', 10, 'application/pdf'),
        $uploader,
    );

    Storage::disk('resources')->assertExists($version->file_path);
    Storage::disk('public')->assertMissing($version->file_path);

    Storage::disk('resources')->delete($version->file_path);
});

test('a temporary URL for a private file is signed and expires, unlike a permanent link', function () {
    Storage::disk('resources')->put('test-private-file/file.pdf', 'contents');

    $temporaryUrl = Storage::disk('resources')->temporaryUrl('test-private-file/file.pdf', now()->addMinutes(5));

    expect($temporaryUrl)
        ->toContain('signature=')
        ->toContain('expires=');
});

test('opening the private file through its signed URL serves it', function () {
    Storage::disk('resources')->put('test-private-file/file.pdf', 'the real pdf contents');

    $temporaryUrl = Storage::disk('resources')->temporaryUrl('test-private-file/file.pdf', now()->addMinutes(5));

    $this->get($temporaryUrl)->assertOk();
});

test('requesting the private file without a valid signature is refused', function () {
    Storage::disk('resources')->put('test-private-file/file.pdf', 'the real pdf contents');

    $this->get('/private-files/test-private-file/file.pdf')->assertForbidden();
});

test('an expired signed URL no longer serves the file', function () {
    Storage::disk('resources')->put('test-private-file/file.pdf', 'the real pdf contents');

    $temporaryUrl = Storage::disk('resources')->temporaryUrl('test-private-file/file.pdf', now()->addMinutes(5));

    $this->travel(10)->minutes();

    $this->get($temporaryUrl)->assertForbidden();
});
