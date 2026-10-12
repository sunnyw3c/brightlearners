<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Learning\Events\ResourceCompleted;
use App\Domains\Learning\Events\ResourceOpened;

class TrackResourceProgress
{
    public function handleOpened(ResourceOpened $event): void
    {
        $progress = $event->progress;
        $progress->loadMissing('profile');

        Analytics::track(
            name: EventName::ResourceOpened,
            properties: [
                'profile_id' => $progress->profile_id,
                'resource_id' => $progress->resource_id,
                'source' => 'web',
            ],
            userId: $progress->profile?->user_id,
            source: 'server',
        );
    }

    public function handleCompleted(ResourceCompleted $event): void
    {
        $progress = $event->progress;
        $progress->loadMissing('profile');

        Analytics::track(
            name: EventName::ResourceCompleted,
            properties: [
                'profile_id' => $progress->profile_id,
                'resource_id' => $progress->resource_id,
            ],
            userId: $progress->profile?->user_id,
            source: 'server',
        );
    }
}
