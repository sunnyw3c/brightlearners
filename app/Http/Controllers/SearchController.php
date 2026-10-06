<?php

namespace App\Http\Controllers;

use App\Domains\Catalog\Queries\ProductSearchQuery;
use App\Domains\Content\Queries\ResourceSearchQuery;
use App\Support\Data\Seo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

/**
 * `/search` (step 5.8): unified search. Phase 6 adds products, merged
 * with resources here so neither query has to know about the other;
 * Phase 12 adds articles the same way.
 */
class SearchController extends Controller
{
    private const PER_PAGE = 12;

    public function __invoke(Request $request, ResourceSearchQuery $resourceQuery, ProductSearchQuery $productQuery): Response
    {
        $term = trim((string) $request->query('q', ''));
        $page = (int) $request->integer('page', 1);

        if ($term === '') {
            $results = new LengthAwarePaginator([], 0, self::PER_PAGE, $page);
        } else {
            $combined = $resourceQuery->search($term, 1, self::PER_PAGE)->getCollection()
                ->concat($productQuery->search($term, self::PER_PAGE))
                ->values();

            $results = new LengthAwarePaginator(
                $combined->forPage($page, self::PER_PAGE)->values(),
                $combined->count(),
                self::PER_PAGE,
                $page,
            );
        }

        return Inertia::render('search/index', [
            'q' => $term,
            'results' => $results,
            'seo' => new Seo(
                title: $term === '' ? 'Search' : "Search: {$term}",
                description: null,
                canonical: route('search'),
                noindex: true,
            ),
        ]);
    }
}
