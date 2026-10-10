<?php

namespace App\Domains\Membership\Listeners;

use App\Domains\Membership\Actions\ActivateMembership;
use App\Domains\Payments\Events\OrderPaid;

class ActivateMembershipOnOrderPaid
{
    public function __construct(
        private readonly ActivateMembership $activateMembership,
    ) {}

    public function handle(OrderPaid $event): void
    {
        $this->activateMembership->handle($event->order);
    }
}
