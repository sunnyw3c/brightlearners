<?php

namespace App\Domains\Content\Queries;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Who received the version a correction replaces (step 4.9). Returns
 * nobody until Phase 9 adds `downloads` and `entitlements` — there is no
 * record yet of who has received a resource.
 */
class AffectedCustomers
{
    /**
     * @return Collection<int, User>
     */
    public function forResource(int $resourceId): Collection
    {
        return collect();
    }
}
