<?php

namespace App\Domains\Membership\Events;

use App\Domains\Membership\Models\LearningWeek;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LearningWeekReleased
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly LearningWeek $week,
    ) {}
}
