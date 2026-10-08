<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Models\Payment;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Throwable;

class VerifyRazorpayCallback
{
    public function __construct(
        private readonly MarkOrderPaid $markOrderPaid,
    ) {}

    /**
     * @param  array{razorpay_order_id: string, razorpay_payment_id: string, razorpay_signature: string}  $callbackData
     */
    public function handle(Order $order, array $callbackData): Order
    {
        $razorpayOrderId = $callbackData['razorpay_order_id'];
        $razorpayPaymentId = $callbackData['razorpay_payment_id'];
        $razorpaySignature = $callbackData['razorpay_signature'];

        $keySecret = config('services.razorpay.key_secret');
        $keyId = config('services.razorpay.key_id');

        // 1. Signature Verification
        $expectedSignature = hash_hmac('sha256', $razorpayOrderId.'|'.$razorpayPaymentId, $keySecret);
        $signatureValid = hash_equals($expectedSignature, $razorpaySignature);

        if (! $signatureValid && $keyId && class_exists(Api::class) && ! Str::contains($keyId, 'rzp_test_key_id')) {
            try {
                $api = new Api($keyId, $keySecret);
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id' => $razorpayOrderId,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'razorpay_signature' => $razorpaySignature,
                ]);
                $signatureValid = true;
            } catch (SignatureVerificationError $e) {
                $signatureValid = false;
            }
        }

        if (! $signatureValid) {
            throw new InvalidArgumentException('Invalid payment callback signature.');
        }

        // Find or create Payment model record
        $payment = Payment::query()->where('order_id', $order->id)->first() ?? Payment::query()->create([
            'order_id' => $order->id,
            'provider' => 'razorpay',
            'provider_order_id' => $razorpayOrderId,
            'amount' => $order->total,
            'currency' => $order->currency,
        ]);

        // If Razorpay SDK is configured, fetch payment details for verification
        $method = 'card';
        if ($keyId && $keySecret && class_exists(Api::class) && ! Str::contains($keyId, 'rzp_test_key_id')) {
            try {
                $api = new Api($keyId, $keySecret);
                $rzpPayment = $api->payment->fetch($razorpayPaymentId);
                $method = $rzpPayment['method'] ?? 'card';
            } catch (Throwable $e) {
                // Keep fallback method
            }
        }

        // Call MarkOrderPaid action
        return $this->markOrderPaid->handle(
            order: $order,
            payment: $payment,
            capturedAmount: $order->total,
            currency: $order->currency,
            providerPaymentId: $razorpayPaymentId,
            method: $method,
        );
    }
}
