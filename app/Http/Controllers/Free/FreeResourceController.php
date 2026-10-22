<?php

namespace App\Http\Controllers\Free;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Content\Queries\FreeResourceIndexQuery;
use App\Domains\Content\Queries\FreeResourceShowQuery;
use App\Domains\Growth\Services\SeoBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Free\FreeResourceIndexRequest;
use App\Support\Data\ResourceCard;
use App\Support\Data\Seo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FreeResourceController extends Controller
{
    /**
     * `/free` (step 5.9): the free resource hub, filtered by query string.
     */
    public function index(FreeResourceIndexRequest $request, FreeResourceIndexQuery $query): Response
    {
        $filters = $request->filters();
        $hasActiveFilters = $request->hasActiveFilters();
        $page = (int) $request->integer('page', 1);

        $resources = $query->handle($filters, $page);

        return Inertia::render('free/index', [
            'resources' => $resources,
            'filters' => $filters,
            'filterOptions' => $query->filterOptions(),
            'seo' => new Seo(
                title: 'Free Printable Worksheets for Class 1-3 | '.config('app.name', 'KiddoZen'),
                description: 'Download free printable PDF worksheets and engaging activities for Class 1 to Class 3. Concept-building practice for Maths, English, and EVS with answer keys.',
                canonical: route('free.index'),
                noindex: $hasActiveFilters,
            ),
        ]);
    }

    /**
     * `/free/{class}/{subject}/{slug}` (step 5.4): the free resource
     * detail page. A request on a non-canonical class/subject pair
     * redirects to the canonical one (step 5.10).
     */
    public function show(string $class, string $subject, string $slug, FreeResourceShowQuery $query): Response|RedirectResponse
    {
        $resource = $query->forSlug($slug);

        $canonicalClass = $resource->primaryClassSlug();
        $canonicalSubject = $resource->primarySubjectSlug();

        abort_if($canonicalClass === null || $canonicalSubject === null, 404);

        if ($class !== $canonicalClass || $subject !== $canonicalSubject) {
            return redirect()->route('free.show', [
                'class' => $canonicalClass,
                'subject' => $canonicalSubject,
                'slug' => $slug,
            ], 301);
        }

        Analytics::track(
            name: EventName::ResourceView,
            properties: [
                'resource_id' => $resource->id,
                'class' => $canonicalClass,
                'subject' => $canonicalSubject,
                'skill' => $resource->primaryMapping()?->skill?->slug ?? 'general',
                'source' => request()->query('source', 'free'),
            ],
            source: 'server',
        );

        return Inertia::render('free/show', [
            'resource' => ResourceCard::fromLearningResource($resource),
            'resourceDetail' => [
                'learning_objective' => $resource->learning_objective,
                'estimated_minutes' => $resource->estimated_minutes,
                'page_count' => $resource->page_count,
                'supplies' => $resource->supplies,
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
            'related' => $query->related($resource)
                ->map(ResourceCard::fromLearningResource(...))
                ->all(),
            'breadcrumbs' => $breadcrumbs = $query->breadcrumbs($resource),
            'seo' => SeoBuilder::forResource($resource, breadcrumbs: $breadcrumbs),
        ]);
    }
}
