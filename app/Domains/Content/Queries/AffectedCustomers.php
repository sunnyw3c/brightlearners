<?php

namespace App\Domains\Content\Queries;

use App\Domains\Access\Models\Download;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Models\ResourceCorrection;
use App\Domains\Content\Models\ResourceVersion;
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

    /**
     * @return Collection<int, User>
     */
    public function forCorrection(ResourceCorrection $correction): Collection
    {
        $oldVersion = ResourceVersion::query()
            ->where('resource_id', $correction->resource_id)
            ->where('version', $correction->version_from)
            ->first();

        $downloadUserIds = Download::query()
            ->where('resource_id', $correction->resource_id)
            ->when($oldVersion !== null, fn ($q) => $q->where('resource_version_id', $oldVersion->id))
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $entitledUserIds = Entitlement::query()
            ->where('resource_id', $correction->resource_id)
            ->whereNull('revoked_at')
            ->pluck('user_id');

        $userIds = $downloadUserIds->merge($entitledUserIds)->unique()->values();

        if ($userIds->isEmpty()) {
            return collect();
        }

        return User::query()->whereIn('id', $userIds)->get();
    }
}
