<?php

namespace App\Domains\Payments\Services;

use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Contracts\PaymentGateway;
use Illuminate\Support\Str;

class FakeGateway implements PaymentGateway
{
    public function createPaymentOrder(Order $order): array
    {
        return [
            'provider' => 'fake',
            'provider_order_id' => 'fake_ord_'.Str::random(12),
            'amount' => $order->total,
            'currency' => $order->currency,
            'status' => 'created',
        ];
    }
}
