<?php

namespace App\Domains\Payments\Events;

use App\Domains\Payments\Models\Payment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentVerified
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Payment $payment,
    ) {}
}
