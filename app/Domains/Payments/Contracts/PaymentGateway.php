<?php

namespace App\Domains\Payments\Contracts;

use App\Domains\Commerce\Models\Order;

interface PaymentGateway
{
    /** @return array{reference: string, amount: int, currency: string} */
    public function createPayment(Order $order): array;
}
