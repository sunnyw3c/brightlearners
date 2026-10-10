<?php

namespace Database\Factories;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Domains\Learning\Models\ResourceProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceProgress>
 */
class ResourceProgressFactory extends Factory
{
    protected $model = ResourceProgress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'learning_profile_id' => LearningProfile::factory(),
            'resource_id' => LearningResource::factory(),
            'status' => ProgressStatus::NotStarted,
            'opened_at' => null,
            'downloaded_at' => null,
            'completed_at' => null,
            'source_context' => null,
        ];
    }

    public function opened(): static
    {
        return $this->state(fn () => [
            'status' => ProgressStatus::Opened,
            'opened_at' => now(),
        ]);
    }

    public function downloaded(): static
    {
        return $this->state(fn () => [
            'status' => ProgressStatus::Downloaded,
            'opened_at' => now()->subMinute(),
            'downloaded_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => ProgressStatus::Completed,
            'opened_at' => now()->subMinutes(10),
            'downloaded_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);
    }
}
