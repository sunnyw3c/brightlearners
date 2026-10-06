<?php

namespace App\Support\Data;

use App\Domains\Content\Models\LearningResource;

/**
 * The common resource-card shape used on the homepage, class, subject,
 * topic and free-resource hub pages (the `ResourceCard` component,
 * docs/plan/phase-05-public-library-ssr-search.md, "Shared components").
 * Property names are snake_case so they match the rest of this app's
 * Inertia props, which mostly come straight from Eloquent attributes.
 */
final class ResourceCard
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $summary,
        public readonly string $type,
        public readonly bool $free,
        public readonly ?string $class_name,
        public readonly ?string $subject_name,
        public readonly ?string $url,
        public readonly ?string $preview_image_url,
        public readonly ?int $preview_width,
        public readonly ?int $preview_height,
        public readonly ?int $estimated_minutes,
        public readonly ?int $page_count,
    ) {}

    public static function fromLearningResource(LearningResource $resource): self
    {
        $preview = $resource->currentVersion?->previews
            ->sortBy('sort_order')
            ->first();

        $classSlug = $resource->primaryClassSlug();
        $subjectSlug = $resource->primarySubjectSlug();

        return new self(
            id: $resource->id,
            title: $resource->title,
            slug: $resource->slug,
            summary: $resource->summary,
            type: $resource->type->value,
            free: $resource->is_free,
            class_name: $resource->primaryMapping()?->schoolClass?->name,
            subject_name: $resource->primaryMapping()?->skill?->topic?->subject?->name,
            url: ($resource->is_free && $classSlug !== null && $subjectSlug !== null)
                ? route('free.show', ['class' => $classSlug, 'subject' => $subjectSlug, 'slug' => $resource->slug])
                : null,
            preview_image_url: $preview?->url(),
            preview_width: $preview?->width,
            preview_height: $preview?->height,
            estimated_minutes: $resource->estimated_minutes,
            page_count: $resource->page_count,
        );
    }
}
