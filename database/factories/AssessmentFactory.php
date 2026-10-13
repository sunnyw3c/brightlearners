<?php

namespace Database\Factories;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Subject;
use App\Domains\Learning\Enums\AssessmentStatus;
use App\Domains\Learning\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Assessment>
 */
class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'class_id' => SchoolClass::factory(),
            'subject_id' => Subject::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->paragraph(),
            'status' => AssessmentStatus::Published,
            'version' => 1,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => AssessmentStatus::Draft,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => AssessmentStatus::Published,
        ]);
    }
}
