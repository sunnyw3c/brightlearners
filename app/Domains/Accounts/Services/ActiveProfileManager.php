<?php

namespace App\Domains\Accounts\Services;

use App\Domains\Accounts\Models\LearningProfile;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class ActiveProfileManager
{
    public const SESSION_KEY = 'active_learning_profile_id';

    public function getActiveProfile(User $user, ?int $requestedProfileId = null): ?LearningProfile
    {
        $profiles = $user->learningProfiles()
            ->where('active', true)
            ->with('schoolClass')
            ->get();

        if ($profiles->isEmpty()) {
            session()->forget(self::SESSION_KEY);

            return null;
        }

        // If a specific profile ID was requested (e.g. query parameter), validate it
        if ($requestedProfileId !== null) {
            $matched = $profiles->firstWhere('id', $requestedProfileId);
            if (! $matched) {
                throw new AuthorizationException('This profile does not belong to you.');
            }
            session([self::SESSION_KEY => $matched->id]);

            return $matched;
        }

        // Check session
        $sessionId = session(self::SESSION_KEY);
        if ($sessionId !== null) {
            $matched = $profiles->firstWhere('id', (int) $sessionId);
            if ($matched !== null) {
                return $matched;
            }
        }

        // Fall back to first active profile
        $first = $profiles->first();
        session([self::SESSION_KEY => $first->id]);

        return $first;
    }

    public function setActiveProfile(User $user, LearningProfile $profile): void
    {
        if ($profile->user_id !== $user->id) {
            throw new AuthorizationException('This profile does not belong to you.');
        }

        session([self::SESSION_KEY => $profile->id]);
    }
}
