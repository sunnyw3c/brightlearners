<?php

namespace App\Domains\Curriculum\Queries;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * The public curriculum tree: class -> subjects -> topics -> skills,
 * using active rows and active mappings only. A skill only appears under
 * a class when `class_skill` maps it there (and is active) — being an
 * active skill under an active topic is not, by itself, enough, because
 * one skill can serve several classes with different wording.
 */
class CurriculumTree
{
    public const CACHE_KEY = 'curriculum:tree';

    public const CACHE_TAG = 'curriculum';

    /**
     * @return Collection<int, SchoolClass>
     */
    public function get(): Collection
    {
        $resolver = fn (): Collection => SchoolClass::query()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (SchoolClass $class): SchoolClass => $this->loadSubjects($class));

        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags([self::CACHE_TAG])->remember(self::CACHE_KEY, now()->addDay(), $resolver);
        }

        return Cache::remember(self::CACHE_KEY, now()->addDay(), $resolver);
    }

    public static function flushCache(): void
    {
        if (Cache::getStore() instanceof TaggableStore) {
            Cache::tags([self::CACHE_TAG])->flush();
        }

        Cache::forget(self::CACHE_KEY);
    }

    private function loadSubjects(SchoolClass $class): SchoolClass
    {
        $subjects = $class->subjects()
            ->active()
            ->wherePivot('active', true)
            ->orderByPivot('sort_order')
            ->get()
            ->map(fn (Subject $subject): Subject => $this->loadTopics($subject, $class));

        return $class->setRelation('subjects', $subjects);
    }

    private function loadTopics(Subject $subject, SchoolClass $class): Subject
    {
        $topics = $subject->topics()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Topic $topic): Topic => $this->loadSkills($topic, $class));

        return $subject->setRelation('topics', $topics);
    }

    private function loadSkills(Topic $topic, SchoolClass $class): Topic
    {
        $skills = $topic->skills()
            ->active()
            ->whereHas('classes', function ($query) use ($class): void {
                $query->whereKey($class->id)->where('class_skill.active', true);
            })
            ->orderBy('sort_order')
            ->get();

        return $topic->setRelation('skills', $skills);
    }
}
