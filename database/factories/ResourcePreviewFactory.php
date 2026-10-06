<?php

namespace Database\Factories;

use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceVersion;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ResourcePreview>
 */
#[UseModel(ResourcePreview::class)]
class ResourcePreviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_version_id' => ResourceVersion::factory(),
            'page_no' => 1,
            'image_path' => sprintf('0/%s.webp', Str::uuid()),
            'width' => 816,
            'height' => 1056,
            'sort_order' => 0,
        ];
    }
}
