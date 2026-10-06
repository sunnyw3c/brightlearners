<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use Carbon\CarbonInterface;
use Illuminate\Validation\ValidationException;

/**
 * Approved -> Scheduled (step 4.6): a publish time is set.
 */
class ScheduleResourcePublish
{
    public function handle(LearningResource $resource, CarbonInterface $publishAt): LearningResource
    {
        if ($resource->status !== ResourceStatus::Approved) {
            throw ValidationException::withMessages([
                'status' => 'Only an approved resource can be scheduled.',
            ]);
        }

        $resource->update([
            'status' => ResourceStatus::Scheduled,
            'scheduled_for' => $publishAt,
        ]);

        return $resource;
    }
}
