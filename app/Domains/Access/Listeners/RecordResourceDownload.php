<?php

namespace App\Domains\Access\Listeners;

use App\Domains\Access\Events\ResourceDownloaded;
use App\Domains\Access\Models\Download;

class RecordResourceDownload
{
    public function handle(ResourceDownloaded $event): void
    {
        $ip = request()->ip();

        Download::query()->create([
            'user_id' => $event->userId,
            'resource_id' => $event->resource->id,
            'resource_version_id' => $event->version->id,
            'entitlement_id' => $event->entitlementId,
            'access_source' => $event->accessSource,
            'variant' => $event->variant,
            'ip_hash' => $ip ? md5($ip) : null,
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);
    }
}
