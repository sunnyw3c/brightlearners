<?php

namespace Database\Factories;

use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Models\LearningProgram;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LearningProgram>
 */
#[UseModel(LearningProgram::class)]
class LearningProgramFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => SchoolClass::factory(),
            'month' => (int) now()->format('n'),
            'year' => (int) now()->format('Y'),
            'title' => fake()->monthName().' Learning Programme',
            'status' => LearningProgramStatus::Published,
            'publish_at' => now(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => LearningProgramStatus::Published,
            'publish_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => LearningProgramStatus::Draft,
            'publish_at' => null,
        ]);
    }
}
