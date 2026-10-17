<?php

use App\Domains\Content\Actions\ReplaceResourceVersionFiles;
use App\Domains\Content\Actions\UploadResourceVersion;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use App\Support\Models\AuditLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

function fakePdf(string $name = 'worksheet.pdf'): UploadedFile
{
    return UploadedFile::fake()->create($name, 10, 'application/pdf');
}

test('replacing the file on a draft version swaps the file, removes the old one and regenerates previews', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $resource = LearningResource::factory()->create(['created_by' => $uploader->id]);
    $version = app(UploadResourceVersion::class)->handle($resource, fakePdf(), $uploader);
    $oldPath = $version->file_path;

    Event::fake([ResourceVersionUploaded::class]);

    app(ReplaceResourceVersionFiles::class)->handle($version, $uploader, fakePdf('fixed.pdf'), fakePdf('answers.pdf'));

    $version->refresh();
    expect($version->file_path)->not->toBe($oldPath);
    expect($version->answer_file_path)->not->toBeNull();
    Storage::disk('resources')->assertMissing($oldPath);
    Storage::disk('resources')->assertExists([$version->file_path, $version->answer_file_path]);
    Event::assertDispatched(ResourceVersionUploaded::class);

    expect(AuditLog::query()->where('action', 'resource_version.file_replaced')->where('subject_id', $version->id)->exists())->toBeTrue();
});

test('a published version cannot have its files replaced', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $version = ResourceVersion::factory()->published()->create();

    expect(fn () => app(ReplaceResourceVersionFiles::class)->handle($version, $uploader, fakePdf()))
        ->toThrow(ValidationException::class);
});

test('files cannot be replaced while the resource is under review', function () {
    Storage::fake('resources');

    $uploader = User::factory()->create();
    $resource = LearningResource::factory()->create(['status' => ResourceStatus::EducationalReview]);
    $version = ResourceVersion::factory()->create(['resource_id' => $resource->id]);

    expect(fn () => app(ReplaceResourceVersionFiles::class)->handle($version, $uploader, fakePdf()))
        ->toThrow(ValidationException::class);
});
