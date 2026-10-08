<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Data\AccessDecision;
use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Access\Models\Entitlement;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Models\User;

class AccessService
{
    public function __construct(
        private readonly MembershipChecker $membershipChecker,
    ) {}

    public function canAccess(?User $user, LearningResource $resource): AccessDecision
    {
        if ($resource->status !== ResourceStatus::Published || $resource->currentVersion === null) {
            return AccessDecision::deny(AccessDecisionReason::NotPublished);
        }

        if ($resource->is_free) {
            return AccessDecision::allow(AccessDecisionReason::Free, 'free');
        }

        if ($user === null) {
            return AccessDecision::deny(AccessDecisionReason::NoAccess);
        }

        $entitlement = Entitlement::query()
            ->where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->whereNull('revoked_at')
            ->where('starts_at', '<=', now())
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->latest('id')
            ->first();

        if ($entitlement !== null) {
            $source = $entitlement->source_type === 'admin_grant' ? 'admin_grant' : 'purchase';

            return AccessDecision::allow(AccessDecisionReason::Entitled, $source, $entitlement);
        }

        if ($this->membershipChecker->canAccessResource($user, $resource)) {
            return AccessDecision::allow(AccessDecisionReason::Membership, 'membership');
        }

        return AccessDecision::deny(AccessDecisionReason::NoAccess);
    }
}
