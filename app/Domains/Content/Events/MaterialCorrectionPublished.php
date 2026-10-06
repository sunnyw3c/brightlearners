<?php

namespace App\Domains\Content\Events;

use App\Domains\Content\Models\ResourceCorrection;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired when a material correction that requires a customer notice is
 * published. The notice itself is built in Phase 11; here it is only
 * queued (step 4.9).
 */
class MaterialCorrectionPublished implements ShouldDispatchAfterCommit
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ResourceCorrection $correction) {}
}
