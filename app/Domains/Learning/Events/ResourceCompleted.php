<?php

namespace App\Domains\Learning\Events;

use App\Domains\Learning\Models\ResourceProgress;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResourceCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly ResourceProgress $progress,
    ) {}
}
