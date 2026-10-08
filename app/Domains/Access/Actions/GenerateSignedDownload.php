<?php

namespace App\Domains\Access\Actions;

use App\Domains\Access\Data\AccessDecision;
use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class GenerateSignedDownload
{
    public function handle(AccessDecision $decision, LearningResource $resource, string $variant = 'colour', ?User $user = null): string
    {
        if (! $decision->allowed) {
            throw new InvalidArgumentException('Cannot generate download URL for unallowed access decision.');
        }

        $version = $resource->currentVersion;
        if ($version === null) {
            throw new InvalidArgumentException('Resource has no current version.');
        }

        $filePath = match ($variant) {
            'low_ink' => $version->low_ink_path ?? $version->file_path,
            'answer_key' => $version->answer_file_path ?? $version->file_path,
            default => $version->file_path,
        };

        $lifetimeMinutes = (int) config('filesystems.disks.resources.temporary_url_lifetime_minutes', 5);

        $temporaryUrl = Storage::disk('resources')->temporaryUrl(
            $filePath,
            now()->addMinutes($lifetimeMinutes)
        );

        ResourceDownloaded::dispatch(
            $resource,
            $version,
            $user?->id,
            $user === null ? session()->getId() : null,
            $decision->source,
            $variant,
            $decision->entitlement?->id
        );

        return $temporaryUrl;
    }
}
