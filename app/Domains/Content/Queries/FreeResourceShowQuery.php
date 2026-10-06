<?php

namespace App\Domains\Content\Queries;

use App\Domains\Content\Models\LearningResource;
use App\Support\Data\Breadcrumb;
use Illuminate\Support\Collection;

/**
 * The free resource detail page (step 5.4): the resource itself, its
 * breadcrumb trail and its related resources.
 */
class FreeResourceShowQuery
{
    public function __construct(private readonly RelatedResourcesQuery $relatedResources) {}

    public function forSlug(string $slug): LearningResource
    {
        return LearningResource::query()
            ->published()
            ->free()
            ->where('slug', $slug)
            ->with([
                'currentVersion.previews',
                'skillMappings.schoolClass',
                'skillMappings.skill.topic.subject',
            ])
            ->firstOrFail();
    }

    /**
     * @return list<Breadcrumb>
     */
    public function breadcrumbs(LearningResource $resource): array
    {
        $mapping = $resource->primaryMapping();
        $schoolClass = $mapping?->schoolClass;
        $subject = $mapping?->skill?->topic?->subject;
        $topic = $mapping?->skill?->topic;

        $crumbs = [new Breadcrumb('Home', route('home'))];

        if ($schoolClass !== null) {
            $crumbs[] = new Breadcrumb($schoolClass->name, route('learn.class', ['class' => $schoolClass->slug]));
        }

        if ($schoolClass !== null && $subject !== null) {
            $crumbs[] = new Breadcrumb($subject->name, route('learn.subject', [
                'class' => $schoolClass->slug,
                'subject' => $subject->slug,
            ]));
        }

        if ($schoolClass !== null && $subject !== null && $topic !== null) {
            $crumbs[] = new Breadcrumb($topic->name, route('learn.topic', [
                'class' => $schoolClass->slug,
                'subject' => $subject->slug,
                'topic' => $topic->slug,
            ]));
        }

        $crumbs[] = new Breadcrumb($resource->title, null);

        return $crumbs;
    }

    /**
     * @return Collection<int, LearningResource>
     */
    public function related(LearningResource $resource, int $limit = 6): Collection
    {
        return $this->relatedResources->forResource($resource, $limit);
    }
}
