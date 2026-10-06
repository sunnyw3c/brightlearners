<?php

namespace App\Domains\Content\Queries;

use App\Domains\Content\Models\LearningResource;
use Illuminate\Support\Collection;

/**
 * Related-resource logic (step 5.6): same class and skill first, then
 * same class and topic, then same class and subject. Excludes the
 * current resource. Limited to 4-6.
 */
class RelatedResourcesQuery
{
    /**
     * @return Collection<int, LearningResource>
     */
    public function forResource(LearningResource $resource, int $limit = 6): Collection
    {
        $primary = $resource->primaryMapping();

        if ($primary === null) {
            return collect();
        }

        $classId = $primary->class_id;
        $skillId = $primary->skill_id;
        $topicId = $primary->skill?->topic_id;
        $subjectId = $primary->skill?->topic?->subject_id;

        $tiers = [
            fn ($query) => $query->where('skill_id', $skillId),
            fn ($query) => $query->whereHas('skill', fn ($q) => $q->where('topic_id', $topicId)),
            fn ($query) => $query->whereHas('skill.topic', fn ($q) => $q->where('subject_id', $subjectId)),
        ];

        $results = collect();
        $excludeIds = [$resource->id];

        foreach ($tiers as $tierConstraint) {
            $remaining = $limit - $results->count();

            if ($remaining <= 0) {
                break;
            }

            $batch = LearningResource::query()
                ->published()
                ->free()
                ->whereKeyNot($excludeIds)
                ->whereHas('skillMappings', function ($query) use ($classId, $tierConstraint): void {
                    $query->where('class_id', $classId);
                    $tierConstraint($query);
                })
                ->with(['currentVersion.previews', 'skillMappings.schoolClass', 'skillMappings.skill.topic.subject'])
                ->limit($remaining)
                ->get();

            $results = $results->merge($batch);
            $excludeIds = $results->pluck('id')->push($resource->id)->all();
        }

        return $results->take($limit)->values();
    }
}
