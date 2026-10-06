<?php

namespace Database\Factories;

use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ResourceVersion>
 */
#[UseModel(ResourceVersion::class)]
class ResourceVersionFactory extends Factory
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
            'version' => '1.0',
            'file_path' => sprintf('0/v1.0/%s.pdf', Str::uuid()),
            'checksum' => hash('sha256', Str::random()),
            'preview_status' => PreviewStatus::Pending,
            'is_current' => false,
            'created_by' => User::factory(),
        ];
    }

    public function previewReady(): static
    {
        return $this->state(fn (array $attributes): array => [
            'preview_status' => PreviewStatus::Ready,
        ]);
    }

    public function previewFailed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'preview_status' => PreviewStatus::Failed,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes): array => [
            'preview_status' => PreviewStatus::Ready,
            'published_at' => now(),
            'is_current' => true,
        ]);
    }
}
