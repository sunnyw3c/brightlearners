<?php

namespace App\Http\Controllers\Coloring;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Content\Queries\ColoringIndexQuery;
use App\Domains\Content\Queries\ColoringShowQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coloring\ColoringIndexRequest;
use App\Support\Data\ResourceCard;
use App\Support\Data\Seo;
use Inertia\Inertia;
use Inertia\Response;

class ColoringController extends Controller
{
    /**
     * `/coloring`: The dedicated discovery hub for printable coloring pages & books.
     */
    public function index(ColoringIndexRequest $request, ColoringIndexQuery $query): Response
    {
        $filters = $request->filters();
        $hasActiveFilters = $request->hasActiveFilters();
        $page = (int) $request->integer('page', 1);

        $resources = $query->handle($filters, $page);

        $appName = config('app.name', 'KiddoZen');
        $themeName = filled($filters['theme'] ?? null) ? ucwords(str_replace('-', ' ', $filters['theme'])).' ' : '';

        return Inertia::render('coloring/index', [
            'resources' => $resources,
            'filters' => $filters,
            'themes' => $query->themeOptions(),
            'classes' => $query->classOptions(),
            'featuredWorkbooks' => $query->featuredWorkbooks(),
            'seo' => new Seo(
                title: "Free Printable {$themeName}Coloring Pages & Books for Kids | {$appName}",
                description: 'Download free printable PDF coloring pages and themed coloring books. High-contrast line art designed to build fine motor control, phonics, and creativity.',
                canonical: route('coloring.index'),
                noindex: $hasActiveFilters,
            ),
        ]);
    }

    /**
     * `/coloring/{slug}`: The coloring resource detail page with multi-page preview and 1-click download.
     */
    public function show(string $slug, ColoringShowQuery $query): Response
    {
        $resource = $query->forSlug($slug);

        Analytics::track(
            name: EventName::ResourceView,
            properties: [
                'resource_id' => $resource->id,
                'class' => $resource->primaryClassSlug() ?? 'general',
                'subject' => $resource->primarySubjectSlug() ?? 'arts',
                'skill' => $resource->primaryMapping()?->skill?->slug ?? 'general',
                'source' => request()->query('source', 'coloring'),
            ],
            source: 'server',
        );

        $appName = config('app.name', 'KiddoZen');

        return Inertia::render('coloring/show', [
            'resource' => ResourceCard::fromLearningResource($resource),
            'resourceDetail' => [
                'learning_objective' => $resource->learning_objective,
                'estimated_minutes' => $resource->estimated_minutes,
                'page_count' => $resource->page_count,
                'supplies' => $resource->supplies ?: 'Crayons, sketch pens, or colored pencils',
                'has_answer_key' => $resource->has_answer_key,
                'low_ink_available' => $resource->low_ink_available,
                'difficulty' => $resource->difficulty,
                'previews' => $resource->currentVersion?->previews
                    ->sortBy('sort_order')
                    ->map(fn ($preview) => [
                        'url' => $preview->url(),
                        'width' => $preview->width,
                        'height' => $preview->height,
                    ])
                    ->values()
                    ->all() ?? [],
                'download_url' => route('downloads.show', ['resource' => $resource->slug]),
            ],
            'breadcrumbs' => $query->breadcrumbs($resource),
            'related' => $query->related($resource)->map(ResourceCard::fromLearningResource(...)),
            'shopRecommendations' => $query->recommendedProducts($resource),
            'seo' => new Seo(
                title: "{$resource->title} – Free Printable Coloring PDF | {$appName}",
                description: $resource->summary ?: "Download and print {$resource->title} for free. High-resolution vector coloring sheet with clear outlines for kids.",
                canonical: route('coloring.show', ['slug' => $resource->slug]),
            ),
        ]);
    }
}
