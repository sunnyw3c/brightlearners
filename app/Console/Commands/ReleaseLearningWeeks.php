<?php

namespace App\Console\Commands;

use App\Domains\Membership\Actions\ReleaseLearningWeek;
use App\Domains\Membership\Models\LearningWeek;
use Illuminate\Console\Command;

class ReleaseLearningWeeks extends Command
{
    protected $signature = 'membership:release-weeks';

    protected $description = 'Release learning weeks whose release time has passed and whose content is ready';

    public function handle(ReleaseLearningWeek $action): int
    {
        $dueWeeks = LearningWeek::query()
            ->where('release_at', '<=', now())
            ->whereNull('released_at')
            ->with(['program', 'items.resource.currentVersion'])
            ->get();

        $releasedCount = 0;
        $blockedCount = 0;

        foreach ($dueWeeks as $week) {
            $success = $action->handle($week);
            if ($success) {
                $releasedCount++;
            } else {
                $blockedCount++;
            }
        }

        $this->info("Released {$releasedCount} learning weeks ({$blockedCount} blocked/pending).");

        return self::SUCCESS;
    }
}
