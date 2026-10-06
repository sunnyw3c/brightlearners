<?php

namespace App\Domains\Content\Queries;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use Illuminate\Support\Collection;

/**
 * The homepage (step 5.1): class cards and featured free resources.
 * "Browse Workbooks" and the membership card link to pages that arrive
 * in Phases 6 and 10 and stay hidden until then.
 */
class HomeContentQuery
{
    /**
     * @return Collection<int, SchoolClass>
     */
    public function classes(): Collection
    {
        return SchoolClass::query()
            ->active()
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug']);
    }

    /**
     * @return Collection<int, LearningResource>
     */
    public function featuredFreeResources(int $limit = 6): Collection
    {
        $query = LearningResource::query()
            ->published()
            ->free()
            ->with(['currentVersion.previews', 'skillMappings.schoolClass', 'skillMappings.skill.topic.subject']);

        $featured = (clone $query)->where('featured', true)->limit($limit)->get();

        if ($featured->count() >= $limit) {
            return $featured;
        }

        $fallback = (clone $query)
            ->whereKeyNot($featured->pluck('id')->all())
            ->latest('published_at')
            ->limit($limit - $featured->count())
            ->get();

        return $featured->merge($fallback);
    }
}
