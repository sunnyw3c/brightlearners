<?php

namespace App\Domains\Commerce\Actions;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Commerce\Data\PriceBreakdown;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Events\CartConvertedToOrder;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Models\CouponUsage;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Services\PricingService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateCartOrder
{
    public function __construct(
        private readonly PricingService $pricing,
        private readonly ApplyCoupon $applyCoupon,
    ) {}

    /**
     * @param  array{billing_name: string, billing_email: string, billing_phone?: string|null}  $billing
     */
    public function handle(Cart $cart, User $user, array $billing): Order
    {
        return DB::transaction(function () use ($cart, $user, $billing): Order {
            $lockedCart = Cart::query()->whereKey($cart->id)->lockForUpdate()->firstOrFail();
            $items = $lockedCart->items()->with('product')->lockForUpdate()->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
            }

            $unavailable = $items->filter(fn ($item): bool => $item->product->status !== ProductStatus::Active);

            if ($unavailable->isNotEmpty()) {
                $lockedCart->items()->whereKey($unavailable->modelKeys())->delete();
                throw ValidationException::withMessages(['cart' => 'An unavailable product was removed from your cart.']);
            }

            $products = $items->pluck('product');
            $coupon = $lockedCart->coupon_id === null
                ? null
                : Coupon::query()->whereKey($lockedCart->coupon_id)->lockForUpdate()->first();

            $withoutCoupon = $this->pricing->calculate($products, $user);

            if ($coupon !== null) {
                $this->applyCoupon->validate($coupon, $withoutCoupon->subtotal, $user);
            }

            $breakdown = $this->pricing->calculate($products, $user, $coupon);

            if ($breakdown->total <= 0) {
                throw ValidationException::withMessages(['coupon' => 'A coupon cannot reduce the order total to zero.']);
            }

            $existing = $this->matchingPendingOrder($user, $products->pluck('id')->all(), $coupon);

            if ($existing !== null) {
                return $existing;
            }

            $order = $this->createOrder($user, $billing, $breakdown, $coupon);

            foreach ($breakdown->lines as $line) {
                $order->items()->create([
                    'product_id' => $line->product->id,
                    'product_name' => $line->product->name,
                    'product_type' => $line->product->type->value,
                    'sku' => $line->product->sku,
                    'unit_price' => $line->unitPrice,
                    'quantity' => 1,
                    'discount' => $line->discount(),
                    'tax' => $line->tax,
                    'total' => $line->total,
                    'metadata' => ['resource_ids' => $line->product->deliverableResources()->pluck('id')->all()],
                ]);
            }

            if ($coupon !== null) {
                CouponUsage::query()->create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'used_at' => now(),
                ]);
            }

            CartConvertedToOrder::dispatch($lockedCart, $order);

            return $order->load('items');
        }, 3);
    }

    /**
     * @param  list<int>  $productIds
     */
    private function matchingPendingOrder(User $user, array $productIds, ?Coupon $coupon): ?Order
    {
        sort($productIds);

        return Order::query()
            ->where('user_id', $user->id)
            ->where('status', OrderStatus::PendingPayment->value)
            ->where('expires_at', '>', now())
            ->where('coupon_id', $coupon?->id)
            ->with('items')
            ->latest('id')
            ->get()
            ->first(function (Order $order) use ($productIds): bool {
                $orderedIds = $order->items->pluck('product_id')->filter()->map(fn ($id): int => (int) $id)->all();
                sort($orderedIds);

                return $orderedIds === $productIds;
            });
    }

    /**
     * @param  array{billing_name: string, billing_email: string, billing_phone?: string|null}  $billing
     */
    private function createOrder(User $user, array $billing, PriceBreakdown $breakdown, ?Coupon $coupon): Order
    {
        $order = Order::query()->create([
            'order_number' => 'PENDING-'.Str::uuid(),
            'user_id' => $user->id,
            'subtotal' => $breakdown->subtotal,
            'discount' => $breakdown->discount(),
            'tax' => $breakdown->tax,
            'total' => $breakdown->total,
            'currency' => $breakdown->currency,
            'coupon_id' => $coupon?->id,
            'coupon_code' => $coupon?->code,
            'billing_name' => $billing['billing_name'],
            'billing_email' => $billing['billing_email'],
            'billing_phone' => $billing['billing_phone'] ?? null,
            'expires_at' => now()->addMinutes((int) config('commerce.order_expiry_minutes')),
        ]);

        $order->forceFill([
            'order_number' => sprintf('%s-%s-%06d', config('commerce.order_number_prefix'), now()->format('Y'), $order->id),
            'status' => OrderStatus::PendingPayment,
        ])->save();

        return $order;
    }
}
