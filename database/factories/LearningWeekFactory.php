<?php

namespace Database\Factories;

use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LearningWeek>
 */
#[UseModel(LearningWeek::class)]
class LearningWeekFactory extends Factory
{
    public function definition(): array
    {
        return [
            'program_id' => LearningProgram::factory(),
            'week_number' => 1,
            'title' => 'Week 1: Fundamentals',
            'emphasis' => 'Core skills practice',
            'release_at' => now()->subDay(),
            'released_at' => now()->subDay(),
        ];
    }

    public function unreleased(): static
    {
        return $this->state(fn () => [
            'release_at' => now()->addDays(7),
            'released_at' => null,
        ]);
    }

    public function dueForRelease(): static
    {
        return $this->state(fn () => [
            'release_at' => now()->subMinutes(10),
            'released_at' => null,
        ]);
    }

    public function released(): static
    {
        return $this->state(fn () => [
            'release_at' => now()->subDay(),
            'released_at' => now()->subDay(),
        ]);
    }
}
