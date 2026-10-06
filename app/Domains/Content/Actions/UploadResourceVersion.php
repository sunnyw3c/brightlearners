<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Services\PdfInfoReader;
use App\Domains\Content\Services\ResourceFileStorage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

/**
 * Uploads a resource's first version (1.0). A resource that already has a
 * version uses `CreateResourceCorrection` instead (step 4.9).
 */
class UploadResourceVersion
{
    public function __construct(
        private readonly ResourceFileStorage $fileStorage,
        private readonly PdfInfoReader $pdfInfoReader,
    ) {}

    public function handle(
        LearningResource $resource,
        UploadedFile $file,
        User $uploader,
        ?UploadedFile $answerFile = null,
        ?UploadedFile $lowInkFile = null,
    ): ResourceVersion {
        if ($resource->versions()->exists()) {
            throw ValidationException::withMessages([
                'file' => 'This resource already has a version. Use "Create correction" to add another.',
            ]);
        }

        $filePath = $this->fileStorage->store($resource, '1.0', $file);
        $answerPath = $answerFile ? $this->fileStorage->store($resource, '1.0', $answerFile, 'answer') : null;
        $lowInkPath = $lowInkFile ? $this->fileStorage->store($resource, '1.0', $lowInkFile, 'low-ink') : null;

        $version = ResourceVersion::query()->create([
            'resource_id' => $resource->id,
            'version' => '1.0',
            'file_path' => $filePath,
            'answer_file_path' => $answerPath,
            'low_ink_path' => $lowInkPath,
            'checksum' => $this->fileStorage->checksum($file),
            'created_by' => $uploader->id,
        ]);

        $this->fillPageCountIfMissing($resource, $filePath);

        ResourceVersionUploaded::dispatch($version);

        return $version;
    }

    private function fillPageCountIfMissing(LearningResource $resource, string $filePath): void
    {
        if ($resource->page_count !== null) {
            return;
        }

        $pageCount = $this->pdfInfoReader->pageCount($this->fileStorage->absolutePath($filePath));

        if ($pageCount !== null) {
            $resource->update(['page_count' => $pageCount]);
        }
    }
}
