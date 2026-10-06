<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Data\AccessDecision;
use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Models\User;

/**
 * The only place that decides whether a user may access a resource's file
 * (docs/reference/architecture.md, "Access rule"). Controllers call this
 * instead of repeating purchase or membership checks.
 *
 * Checked in order:
 * 1. Resource is not published -> deny.
 * 2. Resource is marked free -> allow.
 * 3. User holds a live entitlement (purchase or admin grant) -> allow. Added in Phase 9.
 * 4. User holds a membership covering a released week with this resource -> allow. Added in Phase 10.
 * 5. Otherwise -> deny.
 */
class AccessService
{
    public function canAccess(?User $user, LearningResource $resource): AccessDecision
    {
        if ($resource->status !== ResourceStatus::Published) {
            return AccessDecision::deny(AccessDecisionReason::NotPublished);
        }

        if ($resource->is_free) {
            return AccessDecision::allow(AccessDecisionReason::Free);
        }

        return AccessDecision::deny(AccessDecisionReason::NoAccess);
    }
}
