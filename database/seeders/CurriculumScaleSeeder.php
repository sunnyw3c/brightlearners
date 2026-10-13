<?php

namespace Database\Seeders;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Curriculum\Models\Topic;
use Illuminate\Database\Seeder;

class CurriculumScaleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed School Classes
        $earlyYears = SchoolClass::query()->updateOrCreate(
            ['slug' => 'early-years'],
            ['name' => 'Early Years (Pre-Primary)', 'sort_order' => 0]
        );

        $class4 = SchoolClass::query()->updateOrCreate(
            ['slug' => 'class-4'],
            ['name' => 'Class 4', 'sort_order' => 4]
        );

        $class5 = SchoolClass::query()->updateOrCreate(
            ['slug' => 'class-5'],
            ['name' => 'Class 5', 'sort_order' => 5]
        );

        $maths = Subject::query()->where('slug', 'maths')->first();
        $english = Subject::query()->where('slug', 'english')->first();
        $evs = Subject::query()->where('slug', 'evs')->first();

        // 2. Early Years Content
        if ($maths) {
            $earlyYears->subjects()->syncWithoutDetaching([$maths->id]);
            $topic = Topic::query()->updateOrCreate(
                ['subject_id' => $maths->id, 'slug' => 'early-numbers-shapes'],
                ['name' => 'Early Numbers & Shapes', 'sort_order' => 1]
            );
            $skill = Skill::query()->updateOrCreate(
                ['topic_id' => $topic->id, 'slug' => 'counting-objects-to-10'],
                [
                    'name' => 'Counting Objects up to 10',
                    'learning_objective' => 'Count concrete objects from 1 to 10 reliably.',
                    'difficulty_band' => 'easy',
                    'sort_order' => 1,
                    'active' => true,
                ]
            );
            $earlyYears->skills()->syncWithoutDetaching([$skill->id => [
                'learning_objective' => 'Count concrete objects from 1 to 10 reliably.',
                'difficulty_band' => 'easy',
                'sort_order' => 1,
                'active' => true,
            ]]);
        }

        if ($english) {
            $earlyYears->subjects()->syncWithoutDetaching([$english->id]);
            $topic = Topic::query()->updateOrCreate(
                ['subject_id' => $english->id, 'slug' => 'phonics-letter-sounds'],
                ['name' => 'Phonics & Letter Sounds', 'sort_order' => 1]
            );
            $skill = Skill::query()->updateOrCreate(
                ['topic_id' => $topic->id, 'slug' => 'alphabet-sounds-a-z'],
                [
                    'name' => 'Letter Sound Recognition A–Z',
                    'learning_objective' => 'Identify phonics sounds for all 26 letters.',
                    'difficulty_band' => 'easy',
                    'sort_order' => 1,
                    'active' => true,
                ]
            );
            $earlyYears->skills()->syncWithoutDetaching([$skill->id => [
                'learning_objective' => 'Identify phonics sounds for all 26 letters.',
                'difficulty_band' => 'easy',
                'sort_order' => 1,
                'active' => true,
            ]]);
        }

        // 3. Class 4 Content
        if ($maths) {
            $class4->subjects()->syncWithoutDetaching([$maths->id]);
            $topic = Topic::query()->updateOrCreate(
                ['subject_id' => $maths->id, 'slug' => 'fractions-and-decimals'],
                ['name' => 'Fractions & Decimals', 'sort_order' => 4]
            );
            $skill = Skill::query()->updateOrCreate(
                ['topic_id' => $topic->id, 'slug' => 'understanding-fractions-like-denominators'],
                [
                    'name' => 'Fractions with Like Denominators',
                    'learning_objective' => 'Add and compare simple fractions with like denominators.',
                    'difficulty_band' => 'medium',
                    'sort_order' => 1,
                    'active' => true,
                ]
            );
            $class4->skills()->syncWithoutDetaching([$skill->id => [
                'learning_objective' => 'Add and compare simple fractions with like denominators.',
                'difficulty_band' => 'medium',
                'sort_order' => 1,
                'active' => true,
            ]]);
        }

        // 4. Class 5 Content
        if ($maths) {
            $class5->subjects()->syncWithoutDetaching([$maths->id]);
            $topic = Topic::query()->updateOrCreate(
                ['subject_id' => $maths->id, 'slug' => 'percentages-and-data'],
                ['name' => 'Percentages & Data Handling', 'sort_order' => 5]
            );
            $skill = Skill::query()->updateOrCreate(
                ['topic_id' => $topic->id, 'slug' => 'basic-percentages-and-bar-graphs'],
                [
                    'name' => 'Basic Percentages and Bar Graphs',
                    'learning_objective' => 'Interpret bar charts and calculate simple percentages (10%, 25%, 50%).',
                    'difficulty_band' => 'medium',
                    'sort_order' => 1,
                    'active' => true,
                ]
            );
            $class5->skills()->syncWithoutDetaching([$skill->id => [
                'learning_objective' => 'Interpret bar charts and calculate simple percentages (10%, 25%, 50%).',
                'difficulty_band' => 'medium',
                'sort_order' => 1,
                'active' => true,
            ]]);
        }
    }
}
