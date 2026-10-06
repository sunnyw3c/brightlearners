<?php

namespace Database\Factories;

use App\Domains\Content\Enums\CorrectionSeverity;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceCorrection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceCorrection>
 */
#[UseModel(ResourceCorrection::class)]
class ResourceCorrectionFactory extends Factory
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
            'version_from' => '1.0',
            'version_to' => '1.1',
            'severity' => CorrectionSeverity::Minor,
            'customer_notice_required' => false,
            'notes' => fake()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
