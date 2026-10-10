<?php

namespace App\Domains\Content\Queries;

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Who received the version a correction replaces (step 4.9).
 * Returns real users from downloads and entitlements (Phase 9 step 123).
 */
class AffectedCustomers
{
    /**
     * @return Collection<int, User>
     */
    public function forResource(int $resourceId): Collection
    {
        $downloadUserIds = Download::query()
            ->where('resource_id', $resourceId)
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $entitledUserIds = Entitlement::query()
            ->where('resource_id', $resourceId)
            ->whereNull('revoked_at')
            ->pluck('user_id');

        $userIds = $downloadUserIds->merge($entitledUserIds)->unique()->values();

        if ($userIds->isEmpty()) {
            return collect();
        }

        return User::query()->whereIn('id', $userIds)->get();
    }
}
