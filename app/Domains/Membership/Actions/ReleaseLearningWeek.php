<?php

namespace App\Domains\Membership\Actions;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Events\LearningWeekReleased;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;

class ReleaseLearningWeek
{
    public function handle(LearningWeek $week): bool
    {
        if ($week->released_at !== null) {
            return true;
        }

        $week->loadMissing(['program', 'items.resource.currentVersion']);

        // Must have a published program
        if ($week->program?->status !== LearningProgramStatus::Published) {
            return false;
        }

        // Must have at least one learning item
        if ($week->items->isEmpty()) {
            return false;
        }

        // Every item's resource must be published and have a current version
        foreach ($week->items as $item) {
            $resource = $item->resource;
            if ($resource === null
                || $resource->status !== ResourceStatus::Published
                || $resource->currentVersion === null
            ) {
                return false;
            }
        }

        // Step 10.10: If releasing week 4 and next-month readiness is required
        if ($week->week_number === 4 && config('membership.require_next_month_readiness', false)) {
            $nextMonth = $week->program->month === 12 ? 1 : $week->program->month + 1;
            $nextYear = $week->program->month === 12 ? $week->program->year + 1 : $week->program->year;

            $nextProgramReady = LearningProgram::query()
                ->where('class_id', $week->program->class_id)
                ->where('month', $nextMonth)
                ->where('year', $nextYear)
                ->whereIn('status', [LearningProgramStatus::Ready, LearningProgramStatus::Published])
                ->exists();

            if (! $nextProgramReady) {
                return false;
            }
        }

        $week->released_at = now();
        $week->save();

        LearningWeekReleased::dispatch($week);

        return true;
    }
}
