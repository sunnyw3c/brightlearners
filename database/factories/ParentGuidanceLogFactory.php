<?php

namespace Database\Factories;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Learning\Models\ParentGuidanceLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ParentGuidanceLog>
 */
#[UseModel(ParentGuidanceLog::class)]
class ParentGuidanceLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'profile_id' => LearningProfile::factory(),
            'query' => 'How can I help my child understand 2-digit subtraction with regrouping?',
            'guidance' => 'Try using visual counters or base-ten blocks to represent tens and ones concretely before moving to abstract column subtraction.',
            'safety_flags' => [],
            'escalated' => false,
        ];
    }

    /**
     * State for flagged guidance requiring human escalation.
     */
    public function escalated(): static
    {
        return $this->state(fn (array $attributes): array => [
            'safety_flags' => ['medical_clinical_query_refusal'],
            'escalated' => true,
        ]);
    }
}
