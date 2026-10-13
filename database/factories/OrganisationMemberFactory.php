<?php

namespace Database\Factories;

use App\Domains\Organisations\Models\Organisation;
use App\Domains\Organisations\Models\OrganisationMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganisationMember>
 */
#[UseModel(OrganisationMember::class)]
class OrganisationMemberFactory extends Factory
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
            'user_id' => User::factory(),
            'role' => 'teacher',
            'joined_at' => now(),
        ];
    }

    /**
     * Set member role as admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => 'admin',
        ]);
    }
}
