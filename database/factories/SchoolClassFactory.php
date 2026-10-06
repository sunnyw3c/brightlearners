<?php

namespace Database\Factories;

use App\Domains\Curriculum\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SchoolClass>
 */
#[UseModel(SchoolClass::class)]
class SchoolClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Class '.fake()->unique()->numberBetween(1, 1000);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sort_order' => 0,
            'active' => true,
        ];
    }

    /**
     * Indicate that the class is archived.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'active' => false,
        ]);
    }
}
