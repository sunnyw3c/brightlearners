<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Events\ResourceVersionUploaded;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Content\Services\PdfInfoReader;
use App\Domains\Content\Services\ResourceFileStorage;
use App\Models\User;
use App\Support\Audit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Swaps the files on a version that has never been published — the fix
 * after a reviewer asks for changes, or after uploading the wrong PDF. A
 * published version is immutable and takes a correction instead (step
 * 4.9). Only a Draft resource qualifies, so a file can never change
 * under a reviewer mid-review. The old files were never delivered to a
 * customer, so they are removed from the private disk.
 */
class ReplaceResourceVersionFiles
{
    public function __construct(
        private readonly ResourceFileStorage $fileStorage,
        private readonly PdfInfoReader $pdfInfoReader,
    ) {}

    public function handle(
        ResourceVersion $version,
        User $uploader,
        ?UploadedFile $file = null,
        ?UploadedFile $answerFile = null,
        ?UploadedFile $lowInkFile = null,
    ): ResourceVersion {
        $resource = $version->resource;

        if ($version->isPublished()) {
            throw ValidationException::withMessages([
                'file' => 'A published version cannot be changed. Use "Create correction" instead.',
            ]);
        }

        if ($resource->status !== ResourceStatus::Draft) {
            throw ValidationException::withMessages([
                'file' => 'Files can only be replaced while the resource is a draft.',
            ]);
        }

        if ($file === null && $answerFile === null && $lowInkFile === null) {
            throw ValidationException::withMessages([
                'file' => 'Choose at least one file to replace.',
            ]);
        }

        $before = $version->only(['file_path', 'answer_file_path', 'low_ink_path', 'checksum']);
        $replacedPaths = [];
        $changes = [];

        if ($file !== null) {
            $changes['file_path'] = $this->fileStorage->store($resource, $version->version, $file);
            $changes['checksum'] = $this->fileStorage->checksum($file);
            $changes['preview_status'] = PreviewStatus::Pending;
            $replacedPaths[] = $version->file_path;
        }

        if ($answerFile !== null) {
            $changes['answer_file_path'] = $this->fileStorage->store($resource, $version->version, $answerFile, 'answer');
            $replacedPaths[] = $version->answer_file_path;
        }

        if ($lowInkFile !== null) {
            $changes['low_ink_path'] = $this->fileStorage->store($resource, $version->version, $lowInkFile, 'low-ink');
            $replacedPaths[] = $version->low_ink_path;
        }

        $version->update($changes);

        Storage::disk('resources')->delete(array_values(array_filter($replacedPaths)));

        if ($file !== null) {
            $this->refreshPageCount($version);
        }

        Audit::record(
            'resource_version.file_replaced',
            $version,
            $before,
            $version->only(['file_path', 'answer_file_path', 'low_ink_path', 'checksum']),
            ['resource_id' => $resource->id, 'uploader_id' => $uploader->id]
        );

        if ($file !== null) {
            ResourceVersionUploaded::dispatch($version);
        }

        return $version;
    }

    private function refreshPageCount(ResourceVersion $version): void
    {
        $pageCount = $this->pdfInfoReader->pageCount($this->fileStorage->absolutePath($version->file_path));

        if ($pageCount !== null) {
            $version->resource->update(['page_count' => $pageCount]);
        }
    }
}
