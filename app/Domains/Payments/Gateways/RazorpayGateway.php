<?php

namespace App\Domains\Payments\Gateways;

use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Actions\CreateRazorpayOrder;
use App\Domains\Payments\Contracts\PaymentGateway;

class RazorpayGateway implements PaymentGateway
{
    public function __construct(
        private readonly CreateRazorpayOrder $createRazorpayOrder,
    ) {}

    /**
     * Initialize a gateway payment transaction for an internal order.
     *
     * @return array<string, mixed>
     */
    public function createPaymentOrder(Order $order): array
    {
        $payment = $this->createRazorpayOrder->handle($order);

        $keyId = config('services.razorpay.key_id');

        return [
            'key' => $keyId,
            'amount' => $order->total,
            'currency' => $order->currency,
            'name' => config('app.name', 'BrightLearners'),
            'description' => "Order #{$order->order_number}",
            'order_id' => $payment->provider_order_id,
            'prefill' => [
                'name' => $order->user?->name ?? $order->billing_name,
                'email' => $order->user?->email ?? $order->billing_email,
                'contact' => $order->billing_phone,
            ],
            'notes' => [
                'order_number' => $order->order_number,
                'order_id' => (string) $order->id,
            ],
        ];
    }
}
