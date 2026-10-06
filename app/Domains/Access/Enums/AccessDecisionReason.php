<?php

namespace App\Domains\Access\Enums;

/**
 * The reason behind an `AccessDecision`, in the order
 * `AccessService::canAccess()` checks them
 * (docs/reference/architecture.md, "Access rule").
 */
enum AccessDecisionReason: string
{
    case NotPublished = 'not_published';
    case Free = 'free';
    case Entitled = 'entitled';
    case Membership = 'membership';
    case NoAccess = 'no_access';
}
