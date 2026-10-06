<?php

namespace App\Domains\Content\Events;

use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired after a version's file is stored. Triggers
 * `App\Jobs\GenerateResourcePreviews` (step 4.5).
 */
class ResourceVersionUploaded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ResourceVersion $version) {}
}
