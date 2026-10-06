<?php

namespace App\Domains\Curriculum\Queries;

use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use App\Support\Data\Breadcrumb;
use App\Support\Data\ResourceCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * The subject landing page (step 5.3): topics with real resource counts
 * and the subject's own free resources.
 */
class SubjectLandingQuery
{
    public function forSlugs(string $classSlug, string $subjectSlug): ?Subject
    {
        $class = SchoolClass::query()->active()->where('slug', $classSlug)->first();

        if ($class === null) {
            return null;
        }

        /** @var Subject|null $subject */
        $subject = $class->subjects()
            ->wherePivot('active', true)
            ->active()
            ->where('subjects.slug', $subjectSlug)
            ->first();

        return $subject;
    }

    public function schoolClass(string $classSlug): ?SchoolClass
    {
        return SchoolClass::query()->active()->where('slug', $classSlug)->first();
    }

    /**
     * Every active topic under this subject. A class offers a subject's
     * whole topic list (`class_topic` only supplies optional per-class
     * landing copy, as `CurriculumTree` already assumes for Phase 3) —
     * it is not a prerequisite for a topic to exist here.
     *
     * @return Collection<int, Topic>
     */
    public function topics(SchoolClass $class, Subject $subject): Collection
    {
        return Topic::query()
            ->active()
            ->where('subject_id', $subject->id)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return LengthAwarePaginator<int, ResourceCard>
     */
    public function resources(SchoolClass $class, Subject $subject, int $page = 1, int $perPage = 12): LengthAwarePaginator
    {
        $paginator = LearningResource::query()
            ->published()
            ->free()
            ->whereHas('skillMappings', function (Builder $query) use ($class, $subject): void {
                $query->where('class_id', $class->id)
                    ->whereHas('skill.topic', fn (Builder $q) => $q->where('subject_id', $subject->id));
            })
            ->with(['currentVersion.previews', 'skillMappings.schoolClass', 'skillMappings.skill.topic.subject'])
            ->orderByDesc('featured')
            ->orderBy('title')
            ->paginate($perPage, ['*'], 'page', $page);

        return $paginator->setCollection(
            $paginator->getCollection()->map(ResourceCard::fromLearningResource(...)),
        );
    }

    public function intro(SchoolClass $class, Subject $subject): ?string
    {
        /** @var object{intro: ?string}|null $pivot */
        $pivot = $subject->pivot;

        return $pivot?->intro;
    }

    /**
     * @return list<Breadcrumb>
     */
    public function breadcrumbs(SchoolClass $class, Subject $subject): array
    {
        return [
            new Breadcrumb('Home', route('home')),
            new Breadcrumb($class->name, route('learn.class', ['class' => $class->slug])),
            new Breadcrumb($subject->name, null),
        ];
    }
}
