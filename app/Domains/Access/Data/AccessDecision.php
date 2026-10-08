<?php

namespace App\Domains\Access\Data;

use App\Domains\Access\Enums\AccessDecisionReason;
use App\Domains\Access\Models\Entitlement;

/**
 * The outcome of `AccessService::canAccess()`: whether access is allowed,
 * the source of access, and why.
 */
final class AccessDecision
{
    private function __construct(
        public readonly bool $allowed,
        public readonly AccessDecisionReason $reason,
        public readonly string $source = 'none',
        public readonly ?Entitlement $entitlement = null,
    ) {}

    public static function allow(AccessDecisionReason $reason, string $source = 'free', ?Entitlement $entitlement = null): self
    {
        return new self(true, $reason, $source, $entitlement);
    }

    public static function deny(AccessDecisionReason $reason): self
    {
        return new self(false, $reason, 'none', null);
    }
}
