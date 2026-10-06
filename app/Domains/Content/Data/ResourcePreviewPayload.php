<?php

namespace App\Domains\Content\Data;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use Illuminate\Support\Facades\Storage;

/**
 * What a public page is allowed to know about a resource: its preview
 * images and metadata. It never exposes a file path — only the free
 * download route (`/download/{resource}`, Phase 5/9) reads
 * `resource_versions.file_path`, behind an access check.
 */
final readonly class ResourcePreviewPayload
{
    /**
     * @param  list<string>  $previewImageUrls
     */
    public function __construct(
        public string $title,
        public ?string $summary,
        public string $type,
        public ?string $difficulty,
        public ?int $estimatedMinutes,
        public ?int $pageCount,
        public bool $isFree,
        public bool $hasAnswerKey,
        public array $previewImageUrls,
    ) {}

    public static function fromResource(LearningResource $resource): self
    {
        $previews = $resource->currentVersion?->previews ?? collect();

        return new self(
            title: $resource->title,
            summary: $resource->summary,
            type: $resource->type->value,
            difficulty: $resource->difficulty,
            estimatedMinutes: $resource->estimated_minutes,
            pageCount: $resource->page_count,
            isFree: $resource->is_free,
            hasAnswerKey: $resource->has_answer_key,
            previewImageUrls: $previews
                ->sortBy('sort_order')
                ->map(fn (ResourcePreview $preview): string => Storage::disk('previews')->url($preview->image_path))
                ->values()
                ->all(),
        );
    }
}
