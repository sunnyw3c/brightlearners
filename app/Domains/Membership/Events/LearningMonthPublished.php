<?php

namespace App\Domains\Membership\Events;

use App\Domains\Membership\Models\LearningProgram;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LearningMonthPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly LearningProgram $program,
    ) {}
}
