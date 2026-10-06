<?php

namespace App\Domains\Content\Queries;

use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Support\Data\ResourceCard;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * The `/free` hub (step 5.9): free, published resources filtered by class,
 * subject, topic, type and difficulty. Filters are validated query-string
 * parameters, not separate landing pages.
 */
class FreeResourceIndexQuery
{
    /**
     * @param  array{class?: ?string, subject?: ?string, topic?: ?string, type?: ?string, difficulty?: ?string, price?: ?string, q?: ?string}  $filters
     */
    /**
     * @return LengthAwarePaginator<int, ResourceCard>
     */
    public function handle(array $filters, int $page = 1, int $perPage = 12): LengthAwarePaginator
    {
        $query = LearningResource::query()
            ->published()
            ->free()
            ->with(['currentVersion.previews', 'skillMappings.schoolClass', 'skillMappings.skill.topic.subject']);

        $hasTaxonomyFilter = filled($filters['class'] ?? null)
            || filled($filters['subject'] ?? null)
            || filled($filters['topic'] ?? null);

        if ($hasTaxonomyFilter) {
            // One single resource_skill row must satisfy every given
            // taxonomy filter together, not just any row per filter —
            // otherwise a resource mapped into several classes or
            // subjects could match a combination that none of its
            // individual mappings actually represents.
            $query->whereHas('skillMappings', function ($mappingQuery) use ($filters): void {
                if (filled($filters['class'] ?? null)) {
                    $mappingQuery->whereHas('schoolClass', fn ($q) => $q->where('slug', $filters['class']));
                }

                if (filled($filters['subject'] ?? null)) {
                    $mappingQuery->whereHas('skill.topic.subject', fn ($q) => $q->where('slug', $filters['subject']));
                }

                if (filled($filters['topic'] ?? null)) {
                    $mappingQuery->whereHas('skill.topic', fn ($q) => $q->where('slug', $filters['topic']));
                }
            });
        }

        if (filled($filters['type'] ?? null)) {
            $query->where('type', $filters['type']);
        }

        if (filled($filters['difficulty'] ?? null)) {
            $query->where('difficulty', $filters['difficulty']);
        }

        if (($filters['price'] ?? null) === 'paid') {
            // Every resource on this page is free by definition; an explicit
            // "paid" filter is a deliberate request for an empty result
            // rather than being silently ignored.
            $query->whereKey(null);
        }

        if (filled($filters['q'] ?? null)) {
            $term = $filters['q'];
            $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                ->orWhere('summary', 'like', "%{$term}%"));
        }

        $paginator = $query
            ->orderByDesc('featured')
            ->orderBy('title')
            ->paginate($perPage, ['*'], 'page', $page);

        return $paginator->setCollection(
            $paginator->getCollection()->map(ResourceCard::fromLearningResource(...)),
        );
    }

    /**
     * @return array{classes: Collection<int, SchoolClass>, subjects: Collection<int, Subject>, topics: Collection<int, Topic>, types: list<string>}
     */
    public function filterOptions(): array
    {
        return [
            'classes' => SchoolClass::query()->active()->orderBy('sort_order')->get(['id', 'name', 'slug']),
            'subjects' => Subject::query()->active()->orderBy('sort_order')->get(['id', 'name', 'slug']),
            'topics' => Topic::query()->active()->orderBy('sort_order')->get(['id', 'name', 'slug', 'subject_id']),
            'types' => array_map(fn (ResourceType $case): string => $case->value, ResourceType::cases()),
        ];
    }
}
