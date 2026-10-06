<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Events\MaterialCorrectionPublished;
use App\Domains\Content\Events\ResourcePublished;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * The only way to publish a resource version (step 4.6). Refuses unless
 * every one of these holds:
 * 1. class, skill, learning objective, type and page count are set;
 * 2. the version has an approved review of each required type;
 * 3. the answer-verification reviewer is not the uploader;
 * 4. preview_status is ready and at least one preview exists.
 */
class PublishResourceVersion
{
    /**
     * @var list<ReviewType>
     */
    private const REQUIRED_REVIEW_TYPES = [
        ReviewType::Educational,
        ReviewType::AnswerVerification,
        ReviewType::DesignPrint,
    ];

    public function handle(ResourceVersion $version): ResourceVersion
    {
        $resource = $version->resource;

        $this->assertMetadataComplete($resource);
        $this->assertReviewsApproved($version);
        $this->assertPreviewsReady($version);

        return DB::transaction(function () use ($version, $resource) {
            $previousCurrent = $resource->versions()
                ->where('is_current', true)
                ->whereKeyNot($version->id)
                ->first();

            $version->update(['published_at' => now(), 'is_current' => true]);

            $previousCurrent?->update(['is_current' => false]);

            $resource->update([
                'status' => ResourceStatus::Published,
                'published_at' => $resource->published_at ?? $version->published_at,
            ]);

            if ($previousCurrent !== null) {
                $this->recordCorrection($resource, $previousCurrent, $version);
            }

            ResourcePublished::dispatch($version);

            return $version;
        });
    }

    private function assertMetadataComplete(LearningResource $resource): void
    {
        if (! $resource->hasRequiredMetadata()) {
            throw ValidationException::withMessages([
                'metadata' => 'Class, skill, learning objective, type and page count are required before publishing.',
            ]);
        }
    }

    private function assertReviewsApproved(ResourceVersion $version): void
    {
        foreach (self::REQUIRED_REVIEW_TYPES as $type) {
            $latest = $version->reviews()
                ->where('review_type', $type)
                ->latest('id')
                ->first();

            if (! $latest || $latest->status !== ReviewStatus::Approved) {
                throw ValidationException::withMessages([
                    'reviews' => "The {$type->value} review must be approved before publishing.",
                ]);
            }

            if ($type === ReviewType::AnswerVerification && (int) $latest->reviewer_id === (int) $version->created_by) {
                throw ValidationException::withMessages([
                    'reviews' => 'The answer-verification reviewer cannot be the person who uploaded the file.',
                ]);
            }
        }
    }

    private function assertPreviewsReady(ResourceVersion $version): void
    {
        if ($version->preview_status !== PreviewStatus::Ready || $version->previews()->doesntExist()) {
            throw ValidationException::withMessages([
                'previews' => 'Preview images must be generated before publishing.',
            ]);
        }
    }

    private function recordCorrection(LearningResource $resource, ResourceVersion $from, ResourceVersion $to): void
    {
        $correction = ResourceCorrection::query()->create([
            'resource_id' => $resource->id,
            'version_from' => $from->version,
            'version_to' => $to->version,
            'severity' => $to->correction_severity ?? CorrectionSeverity::Minor,
            'customer_notice_required' => $to->customer_notice_required,
            'notes' => $to->change_notes,
            'created_by' => $to->created_by,
        ]);

        if ($correction->severity === CorrectionSeverity::Material && $correction->customer_notice_required) {
            MaterialCorrectionPublished::dispatch($correction);
        }
    }
}
