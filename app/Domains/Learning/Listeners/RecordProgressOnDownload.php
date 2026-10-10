<?php

namespace App\Domains\Learning\Listeners;

use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Learning\Actions\RecordProgress;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class RecordProgressOnDownload implements ShouldQueue
{
    public function __construct(
        private readonly RecordProgress $recordProgress,
    ) {}

    public function handle(ResourceDownloaded $event): void
    {
        if ($event->userId === null) {
            return;
        }

        $user = User::find($event->userId);
        if ($user === null) {
            return;
        }

        $sessionProfileId = session('active_learning_profile_id');
        $profile = null;

        if ($sessionProfileId !== null) {
            $profile = $user->learningProfiles()->where('active', true)->where('id', $sessionProfileId)->first();
        }

        if ($profile === null) {
            $resourceClassIds = $event->resource->skillMappings()->pluck('class_id');
            if ($resourceClassIds->isNotEmpty()) {
                $profile = $user->learningProfiles()
                    ->where('active', true)
                    ->whereIn('class_id', $resourceClassIds)
                    ->first();
            }
        }

        if ($profile === null) {
            $profile = $user->learningProfiles()->where('active', true)->first();
        }

        if ($profile === null) {
            return;
        }

        try {
            $this->recordProgress->handle(
                $user,
                $profile,
                $event->resource,
                ProgressStatus::Downloaded,
                'resource_download'
            );
        } catch (Throwable) {
            // Downloading should not fail if progress recording encounters an issue
        }
    }
}
