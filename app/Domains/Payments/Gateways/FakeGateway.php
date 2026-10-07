<?php

namespace App\Domains\Payments\Gateways;

use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Contracts\PaymentGateway;

class FakeGateway implements PaymentGateway
{
    public function createPayment(Order $order): array
    {
        return [
            'reference' => 'fake_'.$order->order_number,
            'amount' => $order->total,
            'currency' => $order->currency,
        ];
    }
}
