<?php

namespace App\Domains\Payments\Events;

use App\Domains\Payments\Models\Refund;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefundCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Refund $refund,
    ) {}
}
