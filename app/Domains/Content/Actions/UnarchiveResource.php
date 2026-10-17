<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use Illuminate\Validation\ValidationException;

/**
 * Brings an archived resource back. Its current version already passed
 * every review gate and its files are immutable, so it goes straight back
 * to Published. A resource with no published version returns to Draft
 * and goes through review as normal (T-18 in docs/tracking/decisions.md).
 */
class UnarchiveResource
{
    public function handle(LearningResource $resource): LearningResource
    {
        if ($resource->status !== ResourceStatus::Archived) {
            throw ValidationException::withMessages([
                'status' => 'Only an archived resource can be restored from the archive.',
            ]);
        }

        $resource->update([
            'status' => $resource->currentVersion()->whereNotNull('published_at')->exists()
                ? ResourceStatus::Published
                : ResourceStatus::Draft,
        ]);

        return $resource;
    }
}
