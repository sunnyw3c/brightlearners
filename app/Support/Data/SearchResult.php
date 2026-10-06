<?php

namespace App\Support\Data;

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\LearningResource;

/**
 * One row in unified search results. Phases 6 and 12 build their own
 * `fromX()` constructors (products, articles) so the search page never
 * has to change shape (docs/plan/phase-05-public-library-ssr-search.md,
 * step 5.8).
 */
final class SearchResult
{
    public function __construct(
        public readonly string $type,
        public readonly string $title,
        public readonly string $summary,
        public readonly string $url,
        public readonly ?string $class,
        public readonly bool $free,
    ) {}

    public static function fromLearningResource(LearningResource $resource): self
    {
        return new self(
            type: 'resource',
            title: $resource->title,
            summary: (string) $resource->summary,
            url: route('free.show', [
                'class' => $resource->primaryClassSlug(),
                'subject' => $resource->primarySubjectSlug(),
                'slug' => $resource->slug,
            ]),
            class: $resource->primaryMapping()?->schoolClass?->name,
            free: $resource->is_free,
        );
    }

    public static function fromProduct(Product $product): self
    {
        return new self(
            type: 'product',
            title: $product->name,
            summary: (string) $product->short_description,
            url: route('shop.show', ['class' => $product->classSlug(), 'slug' => $product->slug]),
            class: $product->primaryClass?->name,
            free: false,
        );
    }
}
