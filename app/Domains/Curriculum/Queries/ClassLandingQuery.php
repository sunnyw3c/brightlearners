<?php

namespace App\Domains\Curriculum\Queries;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Support\Data\Breadcrumb;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * The class landing page (step 5.2, design-reference.md "Class page"):
 * subject chips, popular topics and featured free resources.
 */
class ClassLandingQuery
{
    public function forSlug(string $slug): ?SchoolClass
    {
        return SchoolClass::query()->active()->where('slug', $slug)->first();
    }

    /**
     * Subject chips, in the order `class_subject` sets.
     *
     * @return Collection<int, Subject>
     */
    public function subjects(SchoolClass $class): Collection
    {
        return $class->subjects()
            ->wherePivot('active', true)
            ->active()
            ->orderByPivot('sort_order')
            ->get();
    }

    /**
     * Topics under this class, each carrying a real `resource_count`
     * (published, free resources mapped to that topic for this class),
     * most popular first.
     *
     * @return Collection<int, Topic>
     */
    public function popularTopics(SchoolClass $class, int $limit = 6): Collection
    {
        $counts = DB::table('resource_skill')
            ->join('skills', 'skills.id', '=', 'resource_skill.skill_id')
            ->join('resources', 'resources.id', '=', 'resource_skill.resource_id')
            ->where('resource_skill.class_id', $class->id)
            ->where('resources.status', 'published')
            ->where('resources.is_free', true)
            ->selectRaw('skills.topic_id as topic_id, count(distinct resources.id) as resource_count')
            ->groupBy('skills.topic_id')
            ->pluck('resource_count', 'topic_id');

        return Topic::query()
            ->active()
            ->whereHas('subject.classes', fn (Builder $query) => $query
                ->whereKey($class->id)
                ->where('class_subject.active', true))
            ->get()
            ->map(function (Topic $topic) use ($counts): Topic {
                $topic->setAttribute('resource_count', (int) ($counts[$topic->id] ?? 0));

                return $topic;
            })
            ->sortByDesc('resource_count')
            ->take($limit)
            ->values();
    }

    /**
     * Featured free resources for this class (`resources.featured`),
     * topped up with the latest published ones so the section is never
     * empty (step 5.3).
     *
     * @return Collection<int, LearningResource>
     */
    public function featuredFreeResources(SchoolClass $class, int $limit = 6): Collection
    {
        $base = LearningResource::query()
            ->published()
            ->free()
            ->whereHas('skillMappings', fn (Builder $query) => $query->where('class_id', $class->id))
            ->with(['currentVersion.previews', 'skillMappings.schoolClass', 'skillMappings.skill.topic.subject']);

        $featured = (clone $base)->where('featured', true)->limit($limit)->get();

        if ($featured->count() >= $limit) {
            return $featured;
        }

        $fallback = (clone $base)
            ->whereKeyNot($featured->pluck('id')->all())
            ->latest('published_at')
            ->limit($limit - $featured->count())
            ->get();

        return $featured->merge($fallback);
    }

    public function hasPublishedResource(SchoolClass $class): bool
    {
        return LearningResource::query()
            ->published()
            ->free()
            ->whereHas('skillMappings', fn (Builder $query) => $query->where('class_id', $class->id))
            ->exists();
    }

    /**
     * @return list<Breadcrumb>
     */
    public function breadcrumbs(SchoolClass $class): array
    {
        return [
            new Breadcrumb('Home', route('home')),
            new Breadcrumb($class->name, null),
        ];
    }
}
