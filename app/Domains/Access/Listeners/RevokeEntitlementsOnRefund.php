<?php

namespace App\Domains\Access\Listeners;

use App\Domains\Access\Actions\ReviewOrRevokeEntitlements;
use App\Domains\Payments\Events\RefundCompleted;

class RevokeEntitlementsOnRefund
{
    public function __construct(
        private readonly ReviewOrRevokeEntitlements $reviewOrRevokeEntitlements,
    ) {}

    public function handle(RefundCompleted $event): void
    {
        $this->reviewOrRevokeEntitlements->handle($event->refund);
    }
}
