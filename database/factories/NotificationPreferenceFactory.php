<?php

namespace Database\Factories;

use App\Domains\Accounts\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NotificationPreference>
 */
#[UseModel(NotificationPreference::class)]
class NotificationPreferenceFactory extends Factory
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
            'learning_reminders' => true,
            'membership_notices' => true,
            'marketing_consent' => false,
            'marketing_consent_at' => null,
            'marketing_consent_source' => null,
        ];
    }
}
