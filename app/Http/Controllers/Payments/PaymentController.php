<?php

namespace App\Http\Controllers\Payments;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Payments\Actions\VerifyRazorpayCallback;
use App\Domains\Payments\Contracts\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class PaymentController extends Controller
{
    public function store(Request $request, PaymentGateway $paymentGateway): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        $order = Order::query()
            ->where('id', $validated['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($order->status !== OrderStatus::PendingPayment) {
            return response()->json([
                'message' => 'Order is not in pending payment status.',
            ], 422);
        }

        $payload = $paymentGateway->createPaymentOrder($order);

        return response()->json([
            'payment' => $payload,
        ]);
    }

    public function callback(Request $request, VerifyRazorpayCallback $verifyRazorpayCallback): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $order = Order::query()
            ->where('id', $validated['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        try {
            $verifyRazorpayCallback->handle($order, [
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

            return redirect()->route('orders.success', $order->id)->with('success', 'Payment successful!');
        } catch (Throwable $e) {
            return redirect()->route('orders.payment-pending', $order->id)->with('error', $e->getMessage());
        }
    }
}
