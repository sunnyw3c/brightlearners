<?php

namespace App\Http\Controllers\Account;

use App\Domains\Commerce\Models\Order;
use App\Http\Controllers\Controller;
use App\Support\Money;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = $request->user()->orders()
            ->withCount('items')
            ->latest()
            ->paginate(12)
            ->through(fn (Order $order): array => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'total' => Money::toProp($order->total, $order->currency),
                'items_count' => $order->items_count,
                'created_at' => $order->created_at?->toFormattedDateString(),
            ]);

        return Inertia::render('account/orders/index', ['orders' => $orders]);
    }

    public function show(Request $request, Order $order): Response
    {
        $this->authorize('view', $order);
        $order->load('items');

        return Inertia::render('account/orders/show', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'subtotal' => Money::toProp($order->subtotal, $order->currency),
                'discount' => Money::toProp($order->discount, $order->currency),
                'tax' => Money::toProp($order->tax, $order->currency),
                'total' => Money::toProp($order->total, $order->currency),
                'billing_name' => $order->billing_name,
                'billing_email' => $order->billing_email,
                'created_at' => $order->created_at?->toFormattedDateString(),
                'items' => $order->items->map(fn ($item): array => [
                    'id' => $item->id,
                    'name' => $item->product_name,
                    'type' => $item->product_type,
                    'unit_price' => Money::toProp($item->unit_price, $order->currency),
                    'discount' => Money::toProp($item->discount, $order->currency),
                    'tax' => Money::toProp($item->tax, $order->currency),
                    'total' => Money::toProp($item->total, $order->currency),
                ])->all(),
            ],
        ]);
    }
}
