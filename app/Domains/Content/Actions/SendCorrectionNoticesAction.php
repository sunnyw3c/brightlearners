<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Content\Queries\AffectedCustomers;
use App\Models\User;
use App\Notifications\CorrectionNotice;
use App\Support\Audit;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class SendCorrectionNoticesAction
{
    public function __construct(
        private readonly AffectedCustomers $affectedCustomers,
    ) {}

    /**
     * @return Collection<int, User>
     */
    public function handle(ResourceCorrection $correction): Collection
    {
        if ($correction->notified_at !== null) {
            throw ValidationException::withMessages([
                'correction' => 'Correction notice has already been sent.',
            ]);
        }

        $recipients = $this->affectedCustomers->forCorrection($correction);

        foreach ($recipients as $recipient) {
            $recipient->notify(new CorrectionNotice($correction));
        }

        $correction->updateQuietly([
            'notified_at' => now(),
        ]);

        Audit::record(
            'correction.notified',
            $correction,
            null,
            [
                'recipient_count' => $recipients->count(),
                'notified_at' => now()->toIso8601String(),
            ],
            ['resource_id' => $correction->resource_id]
        );

        return $recipients;
    }
}
