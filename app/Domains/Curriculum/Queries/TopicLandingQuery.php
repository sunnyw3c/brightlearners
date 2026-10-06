<?php

namespace App\Domains\Curriculum\Queries;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Support\Data\Breadcrumb;
use App\Support\Data\ResourceCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * The topic landing page (step 5.3): related skills and the topic's own
 * free resources, using the per-class copy in `class_topic`.
 */
class TopicLandingQuery
{
    public function schoolClass(string $classSlug): ?SchoolClass
    {
        return SchoolClass::query()->active()->where('slug', $classSlug)->first();
    }

    public function subject(SchoolClass $class, string $subjectSlug): ?Subject
    {
        /** @var Subject|null $subject */
        $subject = $class->subjects()
            ->wherePivot('active', true)
            ->active()
            ->where('subjects.slug', $subjectSlug)
            ->first();

        return $subject;
    }

    /**
     * The topic belongs to this class through the subject the class
     * already offers (`class_subject`). `class_topic` only supplies
     * optional landing copy — a topic with no copy still exists, it is
     * just `noindex` until copy or a resource gives it something to show
     * (step 5.3).
     */
    public function topic(Subject $subject, SchoolClass $class, string $topicSlug): ?Topic
    {
        return Topic::query()
            ->active()
            ->where('subject_id', $subject->id)
            ->where('slug', $topicSlug)
            ->first();
    }

    public function intro(SchoolClass $class, Topic $topic): ?string
    {
        /** @var object{intro: ?string}|null $pivot */
        $pivot = $class->topics()
            ->wherePivot('active', true)
            ->whereKey($topic->id)
            ->first()
            ?->pivot;

        return $pivot?->intro;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function skills(SchoolClass $class, Topic $topic): Collection
    {
        return $topic->skills()
            ->active()
            ->whereHas('classes', fn (Builder $query) => $query
                ->whereKey($class->id)
                ->where('class_skill.active', true))
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return LengthAwarePaginator<int, ResourceCard>
     */
    public function resources(SchoolClass $class, Topic $topic, int $page = 1, int $perPage = 12): LengthAwarePaginator
    {
        $paginator = LearningResource::query()
            ->published()
            ->free()
            ->whereHas('skillMappings', function (Builder $query) use ($class, $topic): void {
                $query->where('class_id', $class->id)
                    ->whereHas('skill', fn (Builder $q) => $q->where('topic_id', $topic->id));
            })
            ->with(['currentVersion.previews', 'skillMappings.schoolClass', 'skillMappings.skill.topic.subject'])
            ->orderByDesc('featured')
            ->orderBy('title')
            ->paginate($perPage, ['*'], 'page', $page);

        return $paginator->setCollection(
            $paginator->getCollection()->map(ResourceCard::fromLearningResource(...)),
        );
    }

    /**
     * @return list<Breadcrumb>
     */
    public function breadcrumbs(SchoolClass $class, Subject $subject, Topic $topic): array
    {
        return [
            new Breadcrumb('Home', route('home')),
            new Breadcrumb($class->name, route('learn.class', ['class' => $class->slug])),
            new Breadcrumb($subject->name, route('learn.subject', ['class' => $class->slug, 'subject' => $subject->slug])),
            new Breadcrumb($topic->name, null),
        ];
    }
}
