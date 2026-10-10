<?php

namespace App\Domains\Accounts\Services;

use App\Models\User;

class NotificationGate
{
    public function canSend(User $user, string $kind): bool
    {
        return match ($kind) {
            'transactional' => true,
            'membership_notice' => (bool) ($user->notificationPreference?->membership_notices ?? true),
            'learning_reminder' => (bool) ($user->notificationPreference?->learning_reminders ?? true),
            'marketing' => (bool) ($user->notificationPreference?->marketing_consent ?? false),
            default => false,
        };
    }

    /**
     * @param  array<int, string>  $defaultChannels
     * @return array<int, string>
     */
    public function channels(User $user, string $kind, array $defaultChannels = ['mail', 'database']): array
    {
        return $this->canSend($user, $kind) ? $defaultChannels : [];
    }
}
