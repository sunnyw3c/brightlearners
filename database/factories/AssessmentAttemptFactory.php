<?php

namespace Database\Factories;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Learning\Enums\AttemptStatus;
use App\Domains\Learning\Models\Assessment;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssessmentAttempt>
 */
class AssessmentAttemptFactory extends Factory
{
    protected $model = AssessmentAttempt::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'learning_profile_id' => LearningProfile::factory(),
            'assessment_id' => Assessment::factory(),
            'assessment_version' => 1,
            'status' => AttemptStatus::InProgress,
            'parent_feedback' => null,
            'started_at' => now(),
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => AttemptStatus::Completed,
            'completed_at' => now(),
        ]);
    }
}
