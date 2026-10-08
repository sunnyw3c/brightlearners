<?php

namespace App\Http\Controllers\Account;

use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Http\Controllers\Controller;
use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->with('items')
            ->latest('id')
            ->paginate(15)
            ->through(fn (Order $order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'status_label' => $order->status->label(),
                'subtotal' => Money::toProp($order->subtotal, $order->currency),
                'discount' => Money::toProp($order->discount, $order->currency),
                'tax' => Money::toProp($order->tax, $order->currency),
                'total' => Money::toProp($order->total, $order->currency),
                'created_at' => $order->created_at->format('M d, Y'),
                'items_count' => $order->items->count(),
            ]);

        return Inertia::render('account/orders/index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order): Response
    {
        Gate::authorize('view', $order);

        $order->loadMissing('items');

        return Inertia::render('account/orders/show', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'status_label' => $order->status->label(),
                'subtotal' => Money::toProp($order->subtotal, $order->currency),
                'discount' => Money::toProp($order->discount, $order->currency),
                'tax' => Money::toProp($order->tax, $order->currency),
                'total' => Money::toProp($order->total, $order->currency),
                'coupon_code' => $order->coupon_code,
                'billing_name' => $order->billing_name,
                'billing_email' => $order->billing_email,
                'billing_phone' => $order->billing_phone,
                'billing_state' => $order->billing_state,
                'gstin' => $order->gstin,
                'invoice_number' => $order->invoice_number,
                'created_at' => $order->created_at->format('M d, Y H:i'),
                'expires_at' => $order->expires_at?->format('M d, Y H:i'),
                'items' => $order->items->map(fn (OrderItem $item) => [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'product_type' => $item->product_type,
                    'sku' => $item->sku,
                    'unit_price' => Money::toProp($item->unit_price, $order->currency),
                    'quantity' => $item->quantity,
                    'discount' => Money::toProp($item->discount, $order->currency),
                    'tax' => Money::toProp($item->tax, $order->currency),
                    'total' => Money::toProp($item->total, $order->currency),
                ]),
            ],
        ]);
    }
}
