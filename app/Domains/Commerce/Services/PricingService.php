<?php

namespace App\Domains\Commerce\Services;

use App\Domains\Catalog\Services\ProductPrice;
use App\Domains\Commerce\Data\PriceBreakdown;
use App\Domains\Commerce\Data\PriceBreakdownLine;
use App\Domains\Commerce\Enums\CouponType;
use App\Domains\Commerce\Models\Cart;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Models\User;

class PricingService
{
    private const MEMBER_DISCOUNT_PERCENT = 15;

    public function __construct(
        private readonly MembershipChecker $membershipChecker,
    ) {}

    public function calculate(Cart $cart, ?User $user = null): PriceBreakdown
    {
        $cart->loadMissing(['items.product', 'coupon']);

        $user ??= $cart->user;
        $items = $cart->items;

        if ($items->isEmpty()) {
            return new PriceBreakdown(
                lines: [],
                subtotal: 0,
                memberDiscount: 0,
                couponDiscount: 0,
                discount: 0,
                tax: 0,
                total: 0,
                coupon: $cart->coupon,
                currency: $cart->currency ?? 'INR',
            );
        }

        $currency = $cart->currency ?? 'INR';
        $hasMembership = $this->membershipChecker->hasActiveMembership($user);

        // Step 1: Line unit price & line subtotal
        $lineData = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $product = $item->product;
            $quantity = max(1, $item->quantity);
            $unitPrice = ProductPrice::for($product);
            $lineSubtotal = $unitPrice * $quantity;
            $subtotal += $lineSubtotal;

            $lineData[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_subtotal' => $lineSubtotal,
                'is_member_eligible' => $hasMembership && $product->member_discount_eligible,
            ];
        }

        // Step 2: Member discount
        $totalMemberDiscount = 0;
        foreach ($lineData as $idx => $line) {
            if ($line['is_member_eligible']) {
                $memberDisc = (int) round($line['line_subtotal'] * (self::MEMBER_DISCOUNT_PERCENT / 100), 0, PHP_ROUND_HALF_UP);
            } else {
                $memberDisc = 0;
            }
            $lineData[$idx]['member_discount'] = $memberDisc;
            $totalMemberDiscount += $memberDisc;
        }

        // Step 3: Coupon discount
        $coupon = $cart->coupon;
        $totalCouponDiscount = 0;

        if ($coupon !== null && $coupon->isValid($user, $subtotal)) {
            $remainingSubtotal = max(0, $subtotal - $totalMemberDiscount);

            if ($coupon->type === CouponType::Percent) {
                $totalCouponDiscount = (int) round($remainingSubtotal * ($coupon->value / 100), 0, PHP_ROUND_HALF_UP);
            } else {
                $totalCouponDiscount = min($coupon->value, $remainingSubtotal);
            }
        } else {
            $coupon = null;
        }

        // Distribute coupon discount across lines
        $remainingSubtotal = max(0, $subtotal - $totalMemberDiscount);
        $allocatedCouponDiscount = 0;
        $lineCount = count($lineData);

        foreach ($lineData as $idx => $line) {
            $lineRemaining = max(0, $line['line_subtotal'] - $line['member_discount']);

            if ($remainingSubtotal > 0 && $totalCouponDiscount > 0) {
                if ($idx === $lineCount - 1) {
                    $cDisc = max(0, $totalCouponDiscount - $allocatedCouponDiscount);
                } else {
                    $cDisc = (int) round($totalCouponDiscount * ($lineRemaining / $remainingSubtotal), 0, PHP_ROUND_HALF_UP);
                    $allocatedCouponDiscount += $cDisc;
                }
            } else {
                $cDisc = 0;
            }

            $lineData[$idx]['coupon_discount'] = $cDisc;
        }

        // Recalculate exact total coupon discount from lines
        $totalCouponDiscount = (int) array_sum(array_column($lineData, 'coupon_discount'));

        // Step 4: Tax
        $taxRatePercent = (float) config('commerce.tax_rate_percent', 0.0);
        $totalDiscount = $totalMemberDiscount + $totalCouponDiscount;
        $taxableBase = max(0, $subtotal - $totalDiscount);

        $totalTax = (int) round($taxableBase * ($taxRatePercent / 100), 0, PHP_ROUND_HALF_UP);

        // Distribute tax across lines
        $allocatedTax = 0;
        foreach ($lineData as $idx => $line) {
            $lineDiscount = $line['member_discount'] + $line['coupon_discount'];
            $lineTaxable = max(0, $line['line_subtotal'] - $lineDiscount);

            if ($taxableBase > 0 && $totalTax > 0) {
                if ($idx === $lineCount - 1) {
                    $lTax = max(0, $totalTax - $allocatedTax);
                } else {
                    $lTax = (int) round($totalTax * ($lineTaxable / $taxableBase), 0, PHP_ROUND_HALF_UP);
                    $allocatedTax += $lTax;
                }
            } else {
                $lTax = 0;
            }

            $lineData[$idx]['tax'] = $lTax;
        }

        // Build Breakdown Lines
        $finalLines = [];
        $calculatedOrderTotal = 0;

        foreach ($lineData as $line) {
            $lineDiscount = $line['member_discount'] + $line['coupon_discount'];
            $lineTotal = max(0, $line['line_subtotal'] - $lineDiscount + $line['tax']);
            $calculatedOrderTotal += $lineTotal;

            $finalLines[] = new PriceBreakdownLine(
                product: $line['product'],
                quantity: $line['quantity'],
                unitPrice: $line['unit_price'],
                lineSubtotal: $line['line_subtotal'],
                memberDiscount: $line['member_discount'],
                couponDiscount: $line['coupon_discount'],
                lineDiscount: $lineDiscount,
                tax: $line['tax'],
                lineTotal: $lineTotal,
            );
        }

        $orderTotal = max(0, $subtotal - $totalDiscount + $totalTax);

        // Ensure sum of line totals equals order total
        if (count($finalLines) > 0 && $calculatedOrderTotal !== $orderTotal) {
            $diff = $orderTotal - $calculatedOrderTotal;
            $lastIndex = count($finalLines) - 1;
            $last = $finalLines[$lastIndex];

            $finalLines[$lastIndex] = new PriceBreakdownLine(
                product: $last->product,
                quantity: $last->quantity,
                unitPrice: $last->unitPrice,
                lineSubtotal: $last->lineSubtotal,
                memberDiscount: $last->memberDiscount,
                couponDiscount: $last->couponDiscount,
                lineDiscount: $last->lineDiscount,
                tax: $last->tax,
                lineTotal: max(0, $last->lineTotal + $diff),
            );
        }

        return new PriceBreakdown(
            lines: $finalLines,
            subtotal: $subtotal,
            memberDiscount: $totalMemberDiscount,
            couponDiscount: $totalCouponDiscount,
            discount: $totalDiscount,
            tax: $totalTax,
            total: $orderTotal,
            coupon: $coupon,
            currency: $currency,
        );
    }
}
