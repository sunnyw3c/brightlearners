<?php

namespace Database\Factories;

use App\Domains\Growth\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Redirect>
 */
#[UseModel(Redirect::class)]
class RedirectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromSlug = fake()->unique()->slug(2);
        $toSlug = fake()->unique()->slug(2);

        return [
            'from_path' => "/old-{$fromSlug}",
            'to_path' => "/new-{$toSlug}",
            'status_code' => 301,
            'active' => true,
            'hits' => 0,
        ];
    }
}
