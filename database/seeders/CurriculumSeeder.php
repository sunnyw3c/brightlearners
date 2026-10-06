<?php

namespace Database\Seeders;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds Class 1–3 and the Maths / English / EVS taxonomy from
 * database/seeders/data/curriculum.php. Safe to run again: every row is
 * `updateOrCreate`d by its slug (or, for the class/skill mapping, by the
 * (class, skill) pair), so a second run updates wording rather than
 * duplicating rows.
 */
class CurriculumSeeder extends Seeder
{
    private const CLASSES = ['Class 1', 'Class 2', 'Class 3'];

    public function run(): void
    {
        $classes = $this->seedClasses();
        $rows = require __DIR__.'/data/curriculum.php';

        foreach ($rows as $sortIndex => $row) {
            $class = $classes[$row['class']];
            $subject = $this->seedSubject($row['subject'], $sortIndex);
            $topic = $this->seedTopic($subject, $row['topic'], $sortIndex);
            $skill = $this->seedSkill($topic, $row['skill'], $row['objective'], $row['difficulty'], $sortIndex);

            $this->mapClassToSubject($class, $subject, $sortIndex);
            $this->mapClassToSkill($class, $skill, $row['objective'], $row['difficulty'], $sortIndex);
        }
    }

    /**
     * @return array<string, SchoolClass>
     */
    private function seedClasses(): array
    {
        $classes = [];

        foreach (self::CLASSES as $index => $name) {
            $classes[$name] = SchoolClass::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $index],
            );
        }

        return $classes;
    }

    private function seedSubject(string $name, int $sortOrder): Subject
    {
        return Subject::query()->updateOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'sort_order' => $sortOrder],
        );
    }

    private function seedTopic(Subject $subject, string $name, int $sortOrder): Topic
    {
        return Topic::query()->updateOrCreate(
            ['subject_id' => $subject->id, 'slug' => Str::slug($name)],
            ['name' => $name, 'sort_order' => $sortOrder],
        );
    }

    private function seedSkill(Topic $topic, string $name, string $objective, string $difficulty, int $sortOrder): Skill
    {
        return Skill::query()->updateOrCreate(
            ['topic_id' => $topic->id, 'slug' => Str::slug($name)],
            [
                'name' => $name,
                'learning_objective' => $objective,
                'difficulty_band' => $difficulty,
                'sort_order' => $sortOrder,
            ],
        );
    }

    private function mapClassToSubject(SchoolClass $class, Subject $subject, int $sortOrder): void
    {
        $class->subjects()->syncWithoutDetaching([
            $subject->id => ['sort_order' => $sortOrder, 'active' => true],
        ]);
    }

    private function mapClassToSkill(SchoolClass $class, Skill $skill, string $objective, string $difficulty, int $sortOrder): void
    {
        $class->skills()->syncWithoutDetaching([
            $skill->id => [
                'learning_objective' => $objective,
                'difficulty_band' => $difficulty,
                'sort_order' => $sortOrder,
                'active' => true,
            ],
        ]);
    }
}
