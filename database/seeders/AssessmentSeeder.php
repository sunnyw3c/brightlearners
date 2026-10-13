<?php

namespace Database\Seeders;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Learning\Enums\AssessmentStatus;
use App\Domains\Learning\Enums\QuestionDifficulty;
use App\Domains\Learning\Enums\QuestionType;
use App\Domains\Learning\Models\Assessment;
use App\Domains\Learning\Models\AssessmentQuestion;
use App\Domains\Learning\Models\AssessmentSection;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::query()->get()->keyBy('slug');
        $maths = Subject::query()->where('slug', 'maths')->first();
        $english = Subject::query()->where('slug', 'english')->first();

        if (! $maths || $classes->isEmpty()) {
            return;
        }

        // 1. Class 1 Maths Starter Check
        if (isset($classes['class-1'])) {
            $this->seedClass1MathsCheck($classes['class-1'], $maths);
        }

        // 2. Class 2 Maths Starter Check
        if (isset($classes['class-2'])) {
            $this->seedClass2MathsCheck($classes['class-2'], $maths);
        }

        // 3. Class 3 Maths Starter Check
        if (isset($classes['class-3'])) {
            $this->seedClass3MathsCheck($classes['class-3'], $maths);
        }
    }

    private function seedClass1MathsCheck(SchoolClass $class, Subject $subject): void
    {
        $skills = Skill::query()
            ->whereHas('classes', fn ($q) => $q->where('classes.id', $class->id))
            ->take(3)
            ->get();

        if ($skills->isEmpty()) {
            $skills = Skill::query()->take(3)->get();
        }

        $assessment = Assessment::query()->updateOrCreate(
            ['slug' => 'class-1-maths-starter-check'],
            [
                'class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Class 1 Maths Starter Check',
                'description' => 'A gentle 10-minute check to understand number readiness, basic counting, and simple shapes.',
                'status' => AssessmentStatus::Published,
                'version' => 1,
            ]
        );

        $section1 = AssessmentSection::query()->updateOrCreate(
            ['assessment_id' => $assessment->id, 'title' => 'Numbers and Counting'],
            ['sort_order' => 1]
        );

        $skill1 = $skills->first();
        if ($skill1) {
            AssessmentQuestion::query()->updateOrCreate(
                ['section_id' => $section1->id, 'sort_order' => 1],
                [
                    'skill_id' => $skill1->id,
                    'type' => QuestionType::SingleChoice,
                    'prompt' => 'Count the apples: 🍎 🍎 🍎 🍎 🍎. How many apples are there?',
                    'difficulty' => QuestionDifficulty::Easy,
                    'answer_config' => [
                        'options' => ['3', '4', '5', '6'],
                        'correct_answer' => '5',
                        'explanation' => 'There are 5 apples shown in the row.',
                    ],
                    'points' => 1,
                ]
            );

            AssessmentQuestion::query()->updateOrCreate(
                ['section_id' => $section1->id, 'sort_order' => 2],
                [
                    'skill_id' => $skill1->id,
                    'type' => QuestionType::SingleChoice,
                    'prompt' => 'Which number comes right after 7?',
                    'difficulty' => QuestionDifficulty::Easy,
                    'answer_config' => [
                        'options' => ['6', '8', '9', '10'],
                        'correct_answer' => '8',
                        'explanation' => '8 comes immediately after 7 in counting sequence.',
                    ],
                    'points' => 1,
                ]
            );
        }

        $section2 = AssessmentSection::query()->updateOrCreate(
            ['assessment_id' => $assessment->id, 'title' => 'Simple Addition & Shapes'],
            ['sort_order' => 2]
        );

        $skill2 = $skills->skip(1)->first() ?? $skill1;
        if ($skill2) {
            AssessmentQuestion::query()->updateOrCreate(
                ['section_id' => $section2->id, 'sort_order' => 1],
                [
                    'skill_id' => $skill2->id,
                    'type' => QuestionType::SingleChoice,
                    'prompt' => 'What is 3 + 2?',
                    'difficulty' => QuestionDifficulty::Medium,
                    'answer_config' => [
                        'options' => ['4', '5', '6', '7'],
                        'correct_answer' => '5',
                        'explanation' => '3 plus 2 equals 5.',
                    ],
                    'points' => 1,
                ]
            );
        }
    }

    private function seedClass2MathsCheck(SchoolClass $class, Subject $subject): void
    {
        $skills = Skill::query()
            ->whereHas('classes', fn ($q) => $q->where('classes.id', $class->id))
            ->take(3)
            ->get();

        if ($skills->isEmpty()) {
            $skills = Skill::query()->take(3)->get();
        }

        $assessment = Assessment::query()->updateOrCreate(
            ['slug' => 'class-2-maths-starter-check'],
            [
                'class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Class 2 Maths Starter Check',
                'description' => 'Diagnostic check covering 2-digit numbers, place value, and double-digit addition.',
                'status' => AssessmentStatus::Published,
                'version' => 1,
            ]
        );

        $section1 = AssessmentSection::query()->updateOrCreate(
            ['assessment_id' => $assessment->id, 'title' => 'Place Value & Comparison'],
            ['sort_order' => 1]
        );

        $skill1 = $skills->first();
        if ($skill1) {
            AssessmentQuestion::query()->updateOrCreate(
                ['section_id' => $section1->id, 'sort_order' => 1],
                [
                    'skill_id' => $skill1->id,
                    'type' => QuestionType::SingleChoice,
                    'prompt' => 'In the number 54, what is the value of the digit 5?',
                    'difficulty' => QuestionDifficulty::Medium,
                    'answer_config' => [
                        'options' => ['5', '50', '500', '45'],
                        'correct_answer' => '50',
                        'explanation' => 'The digit 5 is in the tens place, so its value is 50.',
                    ],
                    'points' => 1,
                ]
            );

            AssessmentQuestion::query()->updateOrCreate(
                ['section_id' => $section1->id, 'sort_order' => 2],
                [
                    'skill_id' => $skill1->id,
                    'type' => QuestionType::SingleChoice,
                    'prompt' => 'Which number is greater than 67?',
                    'difficulty' => QuestionDifficulty::Easy,
                    'answer_config' => [
                        'options' => ['59', '66', '71', '60'],
                        'correct_answer' => '71',
                        'explanation' => '71 is greater than 67.',
                    ],
                    'points' => 1,
                ]
            );
        }

        $section2 = AssessmentSection::query()->updateOrCreate(
            ['assessment_id' => $assessment->id, 'title' => 'Operations & Word Problems'],
            ['sort_order' => 2]
        );

        $skill2 = $skills->skip(1)->first() ?? $skill1;
        if ($skill2) {
            AssessmentQuestion::query()->updateOrCreate(
                ['section_id' => $section2->id, 'sort_order' => 1],
                [
                    'skill_id' => $skill2->id,
                    'type' => QuestionType::SingleChoice,
                    'prompt' => 'A box has 14 blue pens and 12 red pens. How many pens in total?',
                    'difficulty' => QuestionDifficulty::Medium,
                    'answer_config' => [
                        'options' => ['24', '26', '28', '30'],
                        'correct_answer' => '26',
                        'explanation' => '14 + 12 = 26 pens in total.',
                    ],
                    'points' => 1,
                ]
            );
        }
    }

    private function seedClass3MathsCheck(SchoolClass $class, Subject $subject): void
    {
        $skills = Skill::query()
            ->whereHas('classes', fn ($q) => $q->where('classes.id', $class->id))
            ->take(3)
            ->get();

        if ($skills->isEmpty()) {
            $skills = Skill::query()->take(3)->get();
        }

        $assessment = Assessment::query()->updateOrCreate(
            ['slug' => 'class-3-maths-starter-check'],
            [
                'class_id' => $class->id,
                'subject_id' => $subject->id,
                'title' => 'Class 3 Maths Starter Check',
                'description' => 'Skill readiness check covering 3-digit operations, introduction to multiplication, and mental arithmetic.',
                'status' => AssessmentStatus::Published,
                'version' => 1,
            ]
        );

        $section = AssessmentSection::query()->updateOrCreate(
            ['assessment_id' => $assessment->id, 'title' => 'Multiplication and Place Value'],
            ['sort_order' => 1]
        );

        $skill = $skills->first();
        if ($skill) {
            AssessmentQuestion::query()->updateOrCreate(
                ['section_id' => $section->id, 'sort_order' => 1],
                [
                    'skill_id' => $skill->id,
                    'type' => QuestionType::SingleChoice,
                    'prompt' => 'What is 6 × 4?',
                    'difficulty' => QuestionDifficulty::Medium,
                    'answer_config' => [
                        'options' => ['20', '22', '24', '26'],
                        'correct_answer' => '24',
                        'explanation' => '6 groups of 4 equals 24.',
                    ],
                    'points' => 1,
                ]
            );
        }
    }
}
