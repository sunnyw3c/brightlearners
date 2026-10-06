<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Validation\ValidationException;

/**
 * Draft -> Educational Review (step 4.6): required metadata is complete
 * and a file is uploaded.
 */
class SubmitResourceForReview
{
    public function handle(LearningResource $resource): LearningResource
    {
        if ($resource->status !== ResourceStatus::Draft) {
            throw ValidationException::withMessages([
                'status' => 'Only a draft resource can be submitted for review.',
            ]);
        }

        if (! $resource->hasRequiredMetadata()) {
            throw ValidationException::withMessages([
                'metadata' => 'Class, skill, learning objective, type and page count are required before review.',
            ]);
        }

        if ($resource->versions()->doesntExist()) {
            throw ValidationException::withMessages([
                'file' => 'Upload a file before submitting for review.',
            ]);
        }

        $resource->update(['status' => ResourceStatus::EducationalReview]);

        return $resource;
    }
}
