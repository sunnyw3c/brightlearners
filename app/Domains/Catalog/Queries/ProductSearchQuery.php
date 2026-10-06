<?php

namespace App\Domains\Catalog\Queries;

use App\Domains\Catalog\Models\Product;
use App\Support\Data\SearchResult;
use Illuminate\Support\Collection;

/**
 * Products in the unified search (docs/plan/phase-06-product-catalogue.md,
 * step 6.11). `SearchController` merges this with
 * `App\Domains\Content\Queries\ResourceSearchQuery` without either query
 * knowing about the other.
 */
class ProductSearchQuery
{
    /**
     * @return Collection<int, SearchResult>
     */
    public function search(string $term, int $limit = 12): Collection
    {
        return Product::search($term)
            ->query(fn ($query) => $query->with('primaryClass'))
            ->take($limit)
            ->get()
            ->map(SearchResult::fromProduct(...));
    }
}
