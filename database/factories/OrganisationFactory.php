<?php

namespace Database\Factories;

use App\Domains\Organisations\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organisation>
 */
#[UseModel(Organisation::class)]
class OrganisationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company().' Academy';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 9999),
            'type' => fake()->randomElement(['school', 'tuition_centre', 'ngo']),
            'owner_id' => User::factory(),
        ];
    }
}
