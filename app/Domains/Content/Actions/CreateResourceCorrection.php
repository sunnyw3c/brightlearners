<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Services\ResourceFileStorage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

/**
 * "Create correction" on a published resource (step 4.9): a new,
 * unpublished version. The live version stays live until this one passes
 * the same reviews and is published by `PublishResourceVersion`, which
 * writes the `resource_corrections` row.
 */
class CreateResourceCorrection
{
    public function __construct(private readonly ResourceFileStorage $fileStorage) {}

    public function handle(
        LearningResource $resource,
        UploadedFile $file,
        User $uploader,
        CorrectionSeverity $severity,
        bool $customerNoticeRequired,
        string $changeNotes,
        bool $rewrite = false,
        ?UploadedFile $answerFile = null,
        ?UploadedFile $lowInkFile = null,
    ): ResourceVersion {
        $currentVersion = $resource->currentVersion;

        if (! $currentVersion) {
            throw ValidationException::withMessages([
                'resource' => 'This resource has no published version to correct.',
            ]);
        }

        $nextVersion = $this->nextVersion($currentVersion->version, $rewrite);

        $filePath = $this->fileStorage->store($resource, $nextVersion, $file);
        $answerPath = $answerFile ? $this->fileStorage->store($resource, $nextVersion, $answerFile, 'answer') : null;
        $lowInkPath = $lowInkFile ? $this->fileStorage->store($resource, $nextVersion, $lowInkFile, 'low-ink') : null;

        $version = ResourceVersion::query()->create([
            'resource_id' => $resource->id,
            'version' => $nextVersion,
            'file_path' => $filePath,
            'answer_file_path' => $answerPath,
            'low_ink_path' => $lowInkPath,
            'checksum' => $this->fileStorage->checksum($file),
            'change_notes' => $changeNotes,
            'correction_severity' => $severity,
            'customer_notice_required' => $customerNoticeRequired,
            'created_by' => $uploader->id,
        ]);

        $resource->update(['status' => ResourceStatus::Draft]);

        ResourceVersionUploaded::dispatch($version);

        return $version;
    }

    private function nextVersion(string $current, bool $rewrite): string
    {
        [$major, $minor] = array_map('intval', explode('.', $current));

        return $rewrite ? ($major + 1).'.0' : "{$major}.".($minor + 1);
    }
}
