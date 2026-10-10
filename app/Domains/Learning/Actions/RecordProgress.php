<?php

namespace App\Domains\Learning\Actions;

use App\Domains\Access\Services\AccessService;
use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Domains\Learning\Events\ResourceCompleted;
use App\Domains\Learning\Events\ResourceOpened;
use App\Domains\Learning\Models\ResourceProgress;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class RecordProgress
{
    public function __construct(
        private readonly AccessService $accessService,
    ) {}

    public function handle(
        User $user,
        LearningProfile $profile,
        LearningResource $resource,
        ProgressStatus $targetStatus,
        ?string $sourceContext = null,
    ): ResourceProgress {
        if ($profile->user_id !== $user->id) {
            throw new AuthorizationException('This learning profile does not belong to you.');
        }

        $access = $this->accessService->canAccess($user, $resource);
        if (! $access->allowed) {
            throw new AuthorizationException('You do not have access to this resource.');
        }

        $progress = ResourceProgress::query()
            ->where('learning_profile_id', $profile->id)
            ->where('resource_id', $resource->id)
            ->first();

        if ($progress === null) {
            $progress = new ResourceProgress([
                'user_id' => $user->id,
                'learning_profile_id' => $profile->id,
                'resource_id' => $resource->id,
                'status' => $targetStatus,
                'source_context' => $sourceContext,
            ]);

            $this->applyTimestampsForStatus($progress, $targetStatus);
            $progress->save();

            $this->dispatchEvents($progress, $targetStatus);

            return $progress;
        }

        // Status only moves forward. It never moves back.
        if (! $progress->status->canAdvanceTo($targetStatus)) {
            return $progress;
        }

        $progress->status = $targetStatus;
        if ($sourceContext !== null) {
            $progress->source_context = $sourceContext;
        }
        $this->applyTimestampsForStatus($progress, $targetStatus);
        $progress->save();

        $this->dispatchEvents($progress, $targetStatus);

        return $progress;
    }

    private function applyTimestampsForStatus(ResourceProgress $progress, ProgressStatus $status): void
    {
        $now = now();

        if (in_array($status, [ProgressStatus::Opened, ProgressStatus::Downloaded, ProgressStatus::Completed], true)) {
            $progress->opened_at ??= $now;
        }

        if (in_array($status, [ProgressStatus::Downloaded, ProgressStatus::Completed], true)) {
            $progress->downloaded_at ??= $now;
        }

        if ($status === ProgressStatus::Completed) {
            $progress->completed_at ??= $now;
        }
    }

    private function dispatchEvents(ResourceProgress $progress, ProgressStatus $status): void
    {
        if ($status === ProgressStatus::Opened) {
            ResourceOpened::dispatch($progress);
        } elseif ($status === ProgressStatus::Completed) {
            ResourceCompleted::dispatch($progress);
        }
    }
}
