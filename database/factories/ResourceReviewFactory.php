<?php

namespace Database\Factories;

use App\Domains\Content\Enums\ReviewStatus;
use App\Domains\Content\Enums\ReviewType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceReview;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceReview>
 */
#[UseModel(ResourceReview::class)]
class ResourceReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => LearningResource::factory(),
            'resource_version_id' => ResourceVersion::factory(),
            'review_type' => ReviewType::Educational,
            'reviewer_id' => User::factory(),
            'status' => ReviewStatus::Pending,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReviewStatus::Approved,
            'reviewed_at' => now(),
        ]);
    }

    public function changesRequested(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReviewStatus::ChangesRequested,
            'reviewed_at' => now(),
        ]);
    }
}
