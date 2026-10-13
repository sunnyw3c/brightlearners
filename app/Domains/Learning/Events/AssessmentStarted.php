<?php

namespace App\Domains\Learning\Events;

use App\Domains\Learning\Models\AssessmentAttempt;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssessmentStarted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly AssessmentAttempt $attempt,
    ) {}
}
