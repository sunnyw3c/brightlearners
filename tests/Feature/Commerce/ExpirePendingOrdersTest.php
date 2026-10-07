<?php

use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Events\OrderExpired;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Models\CouponUsage;
use App\Domains\Commerce\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Event;

test('cancels expired pending orders and releases coupon usage', function () {
    $user = User::factory()->create();
    $coupon = Coupon::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'coupon_id' => $coupon->id,
        'status' => OrderStatus::PendingPayment,
        'expires_at' => now()->subMinute(),
    ]);
    CouponUsage::factory()->create(['coupon_id' => $coupon->id, 'user_id' => $user->id, 'order_id' => $order->id]);
    Event::fake([OrderExpired::class]);

    $this->artisan('orders:expire-pending')->assertSuccessful();

    expect($order->refresh()->status)->toBe(OrderStatus::Cancelled);
    $this->assertDatabaseMissing('coupon_usages', ['order_id' => $order->id]);
    Event::assertDispatched(OrderExpired::class, fn (OrderExpired $event): bool => $event->order->is($order));
});
