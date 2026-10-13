<?php

namespace Database\Factories;

use App\Domains\Organisations\Models\ClassroomLicence;
use App\Domains\Organisations\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassroomLicence>
 */
#[UseModel(ClassroomLicence::class)]
class ClassroomLicenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'teacher_id' => User::factory(),
            'name' => 'Grade '.fake()->numberBetween(1, 5).' Classroom',
            'seat_limit' => 35,
            'printable_rights' => true,
            'redistribution_terms_accepted' => true,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addYear(),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the licence is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes): array => [
            'starts_at' => now()->subYear(),
            'ends_at' => now()->subDay(),
            'status' => 'expired',
        ]);
    }

    /**
     * Indicate that the licence is revoked.
     */
    public function revoked(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'revoked',
        ]);
    }
}
