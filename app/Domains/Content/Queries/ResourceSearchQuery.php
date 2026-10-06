<?php

namespace App\Domains\Content\Queries;

use App\Domains\Content\Models\LearningResource;
use App\Support\Data\SearchResult;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Unified search (step 5.8). Only resources today; Phases 6 and 12 join
 * products and articles without changing `SearchResult`'s shape.
 */
class ResourceSearchQuery
{
    public function search(string $term, int $page = 1, int $perPage = 12): LengthAwarePaginator
    {
        $paginator = LearningResource::search($term)
            ->query(fn ($query) => $query->with([
                'skillMappings.schoolClass',
                'skillMappings.skill.topic.subject',
            ]))
            ->paginate($perPage, 'page', $page);

        return $paginator->setCollection(
            $paginator->getCollection()->map(SearchResult::fromLearningResource(...)),
        );
    }
}
