<?php

namespace App\Domains\Content\Listeners;

use App\Domains\Content\Events\MaterialCorrectionPublished;
use App\Domains\Content\Queries\AffectedCustomers;
use App\Notifications\CorrectionNotice;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCorrectionNoticeOnPublished implements ShouldQueue
{
    public function __construct(
        private readonly AffectedCustomers $affectedCustomers,
    ) {}

    public function handle(MaterialCorrectionPublished $event): void
    {
        $users = $this->affectedCustomers->forResource($event->correction->resource_id);

        foreach ($users as $user) {
            $user->notify(new CorrectionNotice($event->correction));
        }

        $event->correction->updateQuietly([
            'notified_at' => now(),
        ]);
    }
}
