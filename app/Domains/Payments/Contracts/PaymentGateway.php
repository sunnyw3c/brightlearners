<?php

namespace App\Domains\Payments\Contracts;

use App\Domains\Commerce\Models\Order;

interface PaymentGateway
{
    /**
     * Initialize a gateway payment transaction for an internal order.
     *
     * @return array<string, mixed>
     */
    public function createPaymentOrder(Order $order): array;
}
