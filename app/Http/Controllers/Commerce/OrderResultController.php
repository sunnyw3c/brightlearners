<?php

namespace App\Http\Controllers\Commerce;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Http\Controllers\Controller;
use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class OrderResultController extends Controller
{
    public function success(Request $request, Order $order): Response
    {
        Gate::authorize('view', $order);

        $order->loadMissing(['items', 'coupon']);

        $isPaid = $order->status === OrderStatus::Paid || $order->status === OrderStatus::Completed;

        return Inertia::render('orders/success', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'status_label' => $order->status->label(),
                'is_paid' => $isPaid,
                'subtotal' => Money::toProp($order->subtotal, $order->currency),
                'discount' => Money::toProp($order->discount, $order->currency),
                'tax' => Money::toProp($order->tax, $order->currency),
                'total' => Money::toProp($order->total, $order->currency),
                'billing_name' => $order->billing_name,
                'billing_email' => $order->billing_email,
                'created_at' => $order->created_at->format('M d, Y H:i'),
                'items' => $order->items->map(fn (OrderItem $item) => [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'product_type' => $item->product_type,
                    'sku' => $item->sku,
                    'unit_price' => Money::toProp($item->unit_price, $order->currency),
                    'quantity' => $item->quantity,
                    'total' => Money::toProp($item->total, $order->currency),
                ]),
            ],
        ]);
    }

    public function pending(Request $request, Order $order): Response
    {
        Gate::authorize('view', $order);

        return Inertia::render('orders/payment-pending', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'status_label' => $order->status->label(),
                'total' => Money::toProp($order->total, $order->currency),
                'created_at' => $order->created_at->format('M d, Y H:i'),
            ],
        ]);
    }
}
