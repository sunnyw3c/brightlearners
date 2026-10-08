<?php

namespace App\Domains\Access\Events;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired every time a signed download URL is generated.
 * Logged to the `downloads` table in Phase 9.
 */
class ResourceDownloaded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly LearningResource $resource,
        public readonly ResourceVersion $version,
        public readonly ?int $userId = null,
        public readonly ?string $anonymousId = null,
        public readonly string $accessSource = 'free',
        public readonly string $variant = 'colour',
        public readonly ?int $entitlementId = null,
    ) {}
}
