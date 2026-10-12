<?php

namespace App\Domains\Content\Listeners;

use App\Domains\Content\Actions\SendCorrectionNoticesAction;
use App\Domains\Content\Events\MaterialCorrectionPublished;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCorrectionNoticeOnPublished implements ShouldQueue
{
    public function __construct(
        private readonly SendCorrectionNoticesAction $sendCorrectionNotices,
    ) {}

    public function handle(MaterialCorrectionPublished $event): void
    {
        if ($event->correction->notified_at !== null) {
            return;
        }

        $this->sendCorrectionNotices->handle($event->correction);
    }
}
