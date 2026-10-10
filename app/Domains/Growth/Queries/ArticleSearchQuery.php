<?php

namespace App\Domains\Growth\Queries;

use App\Domains\Growth\Models\Article;
use App\Support\Data\SearchResult;
use Illuminate\Support\Collection;

/**
 * Articles in the unified search (step 12.2).
 */
class ArticleSearchQuery
{
    /**
     * @return Collection<int, SearchResult>
     */
    public function search(string $term, int $limit = 12): Collection
    {
        return Article::search($term)
            ->query(fn ($query) => $query->published())
            ->take($limit)
            ->get()
            ->map(SearchResult::fromArticle(...));
    }
}
