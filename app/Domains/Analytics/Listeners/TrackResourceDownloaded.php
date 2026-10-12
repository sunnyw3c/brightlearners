<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;

class TrackResourceDownloaded
{
    public function handle(ResourceDownloaded $event): void
    {
        if ($event->accessSource === 'free') {
            Analytics::track(
                name: EventName::FreeDownload,
                properties: [
                    'resource_id' => $event->resource->id,
                    'version_id' => $event->version->id,
                ],
                userId: $event->userId,
                anonymousId: $event->anonymousId,
                source: 'server',
            );
        } else {
            $profileId = 0;
            if ($event->userId !== null) {
                $profile = LearningProfile::query()->where('user_id', $event->userId)->first();
                $profileId = $profile ? (int) $profile->id : 0;
            }

            Analytics::track(
                name: EventName::ResourceDownloaded,
                properties: [
                    'profile_id' => $profileId,
                    'resource_id' => $event->resource->id,
                    'version_id' => $event->version->id,
                ],
                userId: $event->userId,
                anonymousId: $event->anonymousId,
                source: 'server',
            );
        }
    }
}
