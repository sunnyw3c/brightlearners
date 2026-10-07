<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Commerce\Enums\CouponType;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Commerce\Services\PricingService;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function pricingServiceWithMembership(bool $active): PricingService
{
    $checker = new class($active) implements MembershipChecker
    {
        public function __construct(private readonly bool $active) {}

        public function hasActiveMembership(?User $user): bool
        {
            return $this->active && $user !== null;
        }
    };

    return new PricingService($checker);
}

test('applies a sale price without another discount', function () {
    $product = Product::factory()->active()->onSale(8_000, now()->subDay(), now()->addDay())->create(['regular_price' => 10_000]);

    $breakdown = pricingServiceWithMembership(false)->calculate(collect([$product]));

    expect($breakdown->subtotal)->toBe(8_000)
        ->and($breakdown->discount())->toBe(0)
        ->and($breakdown->total)->toBe(8_000);
});

test('applies coupon, member and combined discounts in the fixed order', function (bool $member, ?CouponType $couponType, int $couponValue, int $expectedTotal) {
    $user = User::factory()->create();
    $product = Product::factory()->active()->create(['regular_price' => 10_000, 'member_discount_eligible' => true]);
    $coupon = $couponType === null ? null : Coupon::factory()->create(['type' => $couponType, 'value' => $couponValue]);

    $breakdown = pricingServiceWithMembership($member)->calculate(collect([$product]), $user, $coupon);

    expect($breakdown->total)->toBe($expectedTotal)
        ->and(collect($breakdown->lines)->sum(fn ($line): int => $line->total))->toBe($breakdown->total);
})->with([
    'coupon only' => [false, CouponType::Percent, 10, 9_000],
    'member only' => [true, null, 0, 8_500],
    'member then coupon' => [true, CouponType::Percent, 10, 7_650],
    'fixed coupon after member' => [true, CouponType::Fixed, 500, 8_000],
]);

test('rounds percentage discounts half up and allocates them exactly across lines', function () {
    $products = collect([
        Product::factory()->active()->create(['regular_price' => 101]),
        Product::factory()->active()->create(['regular_price' => 202]),
    ]);
    $coupon = Coupon::factory()->create(['type' => CouponType::Percent, 'value' => 50]);

    $breakdown = pricingServiceWithMembership(false)->calculate($products, null, $coupon);

    expect($breakdown->couponDiscount)->toBe(152)
        ->and($breakdown->total)->toBe(151)
        ->and(collect($breakdown->lines)->sum(fn ($line): int => $line->couponDiscount))->toBe(152)
        ->and(collect($breakdown->lines)->sum(fn ($line): int => $line->total))->toBe(151);
});
