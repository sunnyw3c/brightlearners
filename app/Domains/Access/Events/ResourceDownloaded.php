<?php

namespace App\Domains\Access\Events;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired every time `DownloadController` hands out a temporary URL. Nothing
 * listens yet: Phase 9 logs it to the `downloads` table and Phase 13
 * counts it (docs/plan/phase-05-public-library-ssr-search.md, step 5.5).
 */
class ResourceDownloaded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly LearningResource $resource,
        public readonly ResourceVersion $version,
        public readonly ?int $userId,
        public readonly ?string $anonymousId,
    ) {}
}
