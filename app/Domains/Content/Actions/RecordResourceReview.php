<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Models\ResourceReview;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * Records a reviewer's verdict for one of the three review gates and
 * advances or reverts the resource's workflow status (step 4.6): any
 * review state falls back to Draft when changes are requested.
 */
class RecordResourceReview
{
    /**
     * The resource status a version waits in for each review type.
     *
     * @var array<string, ResourceStatus>
     */
    private const AWAITING_STATUS = [
        ReviewType::Educational->value => ResourceStatus::EducationalReview,
        ReviewType::AnswerVerification->value => ResourceStatus::AnswerVerification,
        ReviewType::DesignPrint->value => ResourceStatus::DesignPrintReview,
    ];

    /**
     * The resource status a version advances to once a type is approved.
     *
     * @var array<string, ResourceStatus>
     */
    private const NEXT_STATUS = [
        ReviewType::Educational->value => ResourceStatus::AnswerVerification,
        ReviewType::AnswerVerification->value => ResourceStatus::DesignPrintReview,
        ReviewType::DesignPrint->value => ResourceStatus::Approved,
    ];

    public function handle(ResourceVersion $version, ReviewType $type, User $reviewer, ReviewStatus $status, ?string $notes = null): ResourceReview
    {
        $resource = $version->resource;

        if ($resource->status !== self::AWAITING_STATUS[$type->value]) {
            throw ValidationException::withMessages([
                'review_type' => "This resource is not awaiting a {$type->value} review.",
            ]);
        }

        if ($type === ReviewType::AnswerVerification
            && $status === ReviewStatus::Approved
            && (int) $reviewer->id === (int) $version->created_by) {
            throw ValidationException::withMessages([
                'reviewer_id' => 'The answer-verification reviewer cannot be the person who uploaded the file.',
            ]);
        }

        $review = ResourceReview::query()->create([
            'resource_id' => $resource->id,
            'resource_version_id' => $version->id,
            'review_type' => $type,
            'reviewer_id' => $reviewer->id,
            'status' => $status,
            'notes' => $notes,
            'reviewed_at' => now(),
        ]);

        $version->update(['reviewed_by' => $reviewer->id, 'reviewed_at' => now()]);

        $resource->update([
            'status' => $status === ReviewStatus::Approved
                ? self::NEXT_STATUS[$type->value]
                : ResourceStatus::Draft,
        ]);

        return $review;
    }
}
