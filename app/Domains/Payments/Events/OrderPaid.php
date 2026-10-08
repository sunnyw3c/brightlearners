<?php

namespace App\Domains\Payments\Events;

use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Models\Payment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaid
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly Payment $payment,
    ) {}
}
