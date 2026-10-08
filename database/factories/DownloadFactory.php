<?php

namespace Database\Factories;

use App\Domains\Access\Models\Download;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Download>
 */
class DownloadFactory extends Factory
{
    protected $model = Download::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'resource_id' => LearningResource::factory(),
            'resource_version_id' => ResourceVersion::factory(),
            'access_source' => 'purchase',
            'variant' => 'colour',
            'ip_hash' => md5($this->faker->ipv4),
            'user_agent' => $this->faker->userAgent,
            'downloaded_at' => now(),
        ];
    }
}
