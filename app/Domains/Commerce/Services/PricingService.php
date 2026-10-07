<?php

namespace App\Domains\Commerce\Services;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductPrice;
use App\Domains\Commerce\Data\PriceBreakdown;
use App\Domains\Commerce\Data\PriceLine;
use App\Domains\Commerce\Enums\CouponType;
use App\Domains\Commerce\Models\Coupon;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Models\User;
use Illuminate\Support\Collection;

class PricingService
{
    public function __construct(private readonly MembershipChecker $membershipChecker) {}

    /**
     * @param  Collection<int, Product>  $products
     */
    public function calculate(Collection $products, ?User $user = null, ?Coupon $coupon = null): PriceBreakdown
    {
        if ($products->isEmpty()) {
            return new PriceBreakdown([], 0, 0, 0, 0, 0);
        }

        $currency = (string) $products->first()->currency;
        $unitPrices = $products->map(fn (Product $product): int => ProductPrice::for($product))->values();
        $memberDiscounts = $products->map(
            fn (Product $product, int $index): int => $this->membershipChecker->hasActiveMembership($user)
                && $product->member_discount_eligible
                ? $this->percent($unitPrices[$index], (int) config('commerce.member_discount_percent'))
                : 0,
        )->values();

        $afterMember = $unitPrices->map(fn (int $price, int $index): int => $price - $memberDiscounts[$index]);
        $couponDiscountTotal = $this->couponDiscount($coupon, $afterMember->sum());
        $couponDiscounts = $this->allocate($couponDiscountTotal, $afterMember);
        $taxable = $afterMember->map(fn (int $price, int $index): int => max(0, $price - $couponDiscounts[$index]));
        $taxes = $taxable->map(fn (int $price): int => $this->percent($price, (int) config('commerce.tax_percent')));

        $lines = $products->values()->map(function (Product $product, int $index) use ($unitPrices, $memberDiscounts, $couponDiscounts, $taxes, $taxable): PriceLine {
            return new PriceLine(
                product: $product,
                unitPrice: $unitPrices[$index],
                memberDiscount: $memberDiscounts[$index],
                couponDiscount: $couponDiscounts[$index],
                tax: $taxes[$index],
                total: $taxable[$index] + $taxes[$index],
            );
        })->all();

        $subtotal = $unitPrices->sum();
        $memberDiscount = $memberDiscounts->sum();
        $tax = $taxes->sum();

        return new PriceBreakdown(
            lines: $lines,
            subtotal: $subtotal,
            memberDiscount: $memberDiscount,
            couponDiscount: $couponDiscounts->sum(),
            tax: $tax,
            total: max(0, $subtotal - $memberDiscount - $couponDiscounts->sum() + $tax),
            currency: $currency,
        );
    }

    private function percent(int $amount, int $percent): int
    {
        return intdiv(($amount * $percent) + 50, 100);
    }

    private function couponDiscount(?Coupon $coupon, int $amount): int
    {
        if ($coupon === null || $amount <= 0) {
            return 0;
        }

        $discount = $coupon->type === CouponType::Percent
            ? $this->percent($amount, $coupon->value)
            : $coupon->value;

        return min($amount, $discount);
    }

    /**
     * Spread an already-rounded discount with largest remainders, so the
     * line discounts always sum exactly to the order discount.
     *
     * @param  Collection<int, int>  $weights
     * @return Collection<int, int>
     */
    private function allocate(int $amount, Collection $weights): Collection
    {
        $totalWeight = $weights->sum();

        if ($amount === 0 || $totalWeight === 0) {
            return $weights->map(fn (): int => 0);
        }

        $allocations = $weights->map(fn (int $weight): int => intdiv($amount * $weight, $totalWeight));
        $remaining = $amount - $allocations->sum();

        $remainders = $weights->map(fn (int $weight, int $index): array => [
            'index' => $index,
            'remainder' => ($amount * $weight) % $totalWeight,
        ])->sortByDesc('remainder')->values();

        for ($position = 0; $position < $remaining; $position++) {
            $index = $remainders[$position % $remainders->count()]['index'];
            $allocations[$index]++;
        }

        return $allocations->values();
    }
}
