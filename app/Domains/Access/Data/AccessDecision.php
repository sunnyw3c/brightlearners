<?php

namespace App\Domains\Access\Data;

use App\Domains\Access\Enums\AccessDecisionReason;

/**
 * The outcome of `AccessService::canAccess()`: whether access is allowed,
 * and why. Controllers check `allowed` and may use `reason` for logging or
 * messaging; they must not re-derive the decision themselves.
 */
final class AccessDecision
{
    private function __construct(
        public readonly bool $allowed,
        public readonly AccessDecisionReason $reason,
    ) {}

    public static function allow(AccessDecisionReason $reason): self
    {
        return new self(true, $reason);
    }

    public static function deny(AccessDecisionReason $reason): self
    {
        return new self(false, $reason);
    }
}
