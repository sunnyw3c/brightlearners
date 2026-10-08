<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Events\CartConvertedToOrder;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Models\CouponUsage;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Domains\Commerce\Services\CartManager;
use App\Domains\Commerce\Services\PricingService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateCartOrder
{
    public function __construct(
        private readonly CartManager $cartManager,
        private readonly PricingService $pricingService,
    ) {}

    /**
     * @param  array{billing_name: string, billing_email: string, billing_phone?: string|null, billing_state?: string|null, gstin?: string|null}  $billingData
     */
    public function handle(Cart $cart, User $user, array $billingData): Order
    {
        return DB::transaction(function () use ($cart, $user, $billingData): Order {
            $originalCouponId = $cart->coupon_id;

            // 1. Reload & validate cart products (remove inactive items)
            $removedMessages = $this->cartManager->reloadAndValidateCart($cart);

            if ($originalCouponId !== null && $cart->coupon_id === null) {
                throw ValidationException::withMessages([
                    'coupon' => 'The coupon applied to your cart is no longer valid or has reached its use limit.',
                ]);
            }

            $cart->load('items.product');

            if ($cart->items->isEmpty()) {
                $message = ! empty($removedMessages)
                    ? implode(' ', $removedMessages)
                    : 'Your cart is empty.';

                throw ValidationException::withMessages([
                    'cart' => $message,
                ]);
            }

            // 2. Check for existing unexpired pending order for exact same cart contents
            $cartProductIds = $cart->items->pluck('product_id')->sort()->values()->toArray();
            $existingOrder = Order::query()
                ->where('user_id', $user->id)
                ->where('status', OrderStatus::PendingPayment)
                ->where('expires_at', '>', now())
                ->where('coupon_id', $cart->coupon_id)
                ->with('items')
                ->get()
                ->first(function (Order $order) use ($cartProductIds): bool {
                    $orderProductIds = $order->items->pluck('product_id')->sort()->values()->toArray();

                    return $orderProductIds === $cartProductIds;
                });

            if ($existingOrder !== null) {
                return $existingOrder;
            }

            // 3. Lock coupon if set and re-verify validity
            $coupon = null;
            if ($cart->coupon_id !== null) {
                $coupon = Coupon::query()->where('id', $cart->coupon_id)->lockForUpdate()->first();
                $tempBreakdown = $this->pricingService->calculate($cart, $user);

                $reason = null;
                if ($coupon === null || ! $coupon->isValid($user, $tempBreakdown->subtotal, $reason)) {
                    $cart->coupon_id = null;
                    $cart->save();

                    throw ValidationException::withMessages([
                        'coupon' => $reason ?? 'The coupon applied is no longer valid.',
                    ]);
                }
            }

            // 4. Calculate final pricing breakdown
            $breakdown = $this->pricingService->calculate($cart, $user);

            if ($breakdown->total <= 0) {
                throw ValidationException::withMessages([
                    'cart' => 'Orders with a total of zero cannot be processed.',
                ]);
            }

            // 5. Generate human-readable order_number: BL-YYYY-000123
            $prefix = config('commerce.order_number_prefix', 'BL');
            $year = now()->format('Y');
            $pattern = "{$prefix}-{$year}-%";

            $latestOrderNumber = Order::query()
                ->where('order_number', 'like', $pattern)
                ->lockForUpdate()
                ->latest('id')
                ->value('order_number');

            if ($latestOrderNumber !== null) {
                $parts = explode('-', $latestOrderNumber);
                $seq = ((int) end($parts)) + 1;
            } else {
                $seq = 1;
            }

            $orderNumber = sprintf('%s-%s-%06d', $prefix, $year, $seq);

            // 6. Create Order record
            $order = Order::query()->create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'subtotal' => $breakdown->subtotal,
                'discount' => $breakdown->discount,
                'tax' => $breakdown->tax,
                'total' => $breakdown->total,
                'currency' => $breakdown->currency,
                'status' => OrderStatus::PendingPayment,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'billing_name' => $billingData['billing_name'],
                'billing_email' => $billingData['billing_email'],
                'billing_phone' => $billingData['billing_phone'] ?? null,
                'billing_state' => $billingData['billing_state'] ?? null,
                'gstin' => $billingData['gstin'] ?? null,
                'expires_at' => now()->addMinutes(config('commerce.order_expiry_minutes', 30)),
            ]);

            // 7. Create OrderItem snapshots
            foreach ($breakdown->lines as $line) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $line->product->id,
                    'product_name' => $line->product->name,
                    'product_type' => $line->product->type->value,
                    'sku' => $line->product->sku,
                    'unit_price' => $line->unitPrice,
                    'quantity' => $line->quantity,
                    'discount' => $line->lineDiscount,
                    'tax' => $line->tax,
                    'total' => $line->lineTotal,
                    'metadata' => [
                        'deliverable_resource_ids' => $line->product->deliverableResources()->pluck('id')->toArray(),
                    ],
                ]);
            }

            // 8. Record coupon usage
            if ($coupon !== null) {
                CouponUsage::query()->create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'used_at' => now(),
                ]);
            }

            // 9. Fire CartConvertedToOrder event & clear cart
            CartConvertedToOrder::dispatch($order, $cart);

            $cart->items()->delete();
            $cart->coupon_id = null;
            $cart->save();

            return $order;
        });
    }
}
