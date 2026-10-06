<?php

namespace App\Domains\Content\Events;

use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired once `PublishResourceVersion` succeeds. Invalidates caches now;
 * search indexing is added in Phase 5.
 */
class ResourcePublished implements ShouldDispatchAfterCommit
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ResourceVersion $version) {}
}
