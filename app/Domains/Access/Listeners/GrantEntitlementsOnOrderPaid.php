<?php

namespace App\Domains\Access\Listeners;

use App\Domains\Access\Actions\GrantPurchasedEntitlements;
use App\Domains\Payments\Events\OrderPaid;

class GrantEntitlementsOnOrderPaid
{
    public function __construct(
        private readonly GrantPurchasedEntitlements $grantPurchasedEntitlements,
    ) {}

    public function handle(OrderPaid $event): void
    {
        $this->grantPurchasedEntitlements->handle($event->order);
    }
}
