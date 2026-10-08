import { Head, useForm, router } from '@inertiajs/react';
import { FormEvent } from 'react';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { show as cartShowRoute } from '@/routes/cart';
import { store as couponStore, destroy as couponDestroy } from '@/routes/cart/coupon';
import { destroy as itemsDestroy } from '@/routes/cart/items';
import { show as checkoutShowRoute } from '@/routes/checkout';
import { index as shopIndexRoute } from '@/routes/shop';

type MoneyProp = {
    paise: number;
    formatted: string;
};

type BreakdownLine = {
    product_id: number;
    product_name: string;
    product_type: string;
    sku: string | null;
    quantity: number;
    unit_price: MoneyProp;
    line_subtotal: MoneyProp;
    member_discount: MoneyProp;
    coupon_discount: MoneyProp;
    line_discount: MoneyProp;
    tax: MoneyProp;
    line_total: MoneyProp;
};

type PriceBreakdownProp = {
    lines: BreakdownLine[];
    subtotal: MoneyProp;
    member_discount: MoneyProp;
    coupon_discount: MoneyProp;
    discount: MoneyProp;
    tax: MoneyProp;
    total: MoneyProp;
    coupon: {
        id: number;
        code: string;
        type: string;
        value: number;
    } | null;
    currency: string;
};

type Props = {
    breakdown: PriceBreakdownProp;
    notice?: string | null;
};

export default function CartShow({ breakdown, notice }: Props) {
    const couponForm = useForm({
        code: '',
    });

    const handleApplyCoupon = (e: FormEvent) => {
        e.preventDefault();
        couponForm.post(couponStore.url(), {
            preserveScroll: true,
            onSuccess: () => couponForm.reset(),
        });
    };

    const handleRemoveCoupon = () => {
        router.delete(couponDestroy.url(), {
            preserveScroll: true,
        });
    };

    const handleRemoveItem = (productId: number) => {
        router.delete(itemsDestroy.url({ product: productId }), {
            preserveScroll: true,
        });
    };

    const hasItems = breakdown.lines.length > 0;

    return (
        <PublicLayout>
            <Head title="Your Cart — BrightLearners" />

            <div className="mx-auto max-w-4xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-3xl font-bold tracking-tight">Your Cart</h1>
                    <p className="text-sm text-muted-foreground">
                        Review your selected learning products before checkout.
                    </p>
                </header>

                {notice && (
                    <div className="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                        {notice}
                    </div>
                )}

                {hasItems ? (
                    <div className="grid gap-8 lg:grid-cols-3">
                        <div className="space-y-4 lg:col-span-2">
                            <div className="divide-y rounded-2xl border bg-card shadow-sm">
                                {breakdown.lines.map((line) => (
                                    <div
                                        key={line.product_id}
                                        className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5"
                                    >
                                        <div className="space-y-1">
                                            <h3 className="font-semibold text-base">{line.product_name}</h3>
                                            <div className="flex items-center gap-2 text-xs text-muted-foreground">
                                                <span className="capitalize">{line.product_type.replace('_', ' ')}</span>
                                                {line.sku && <span>• SKU: {line.sku}</span>}
                                            </div>
                                        </div>

                                        <div className="flex items-center justify-between sm:justify-end gap-6">
                                            <div className="text-right">
                                                <div className="font-semibold text-base">{line.line_total.formatted}</div>
                                                {line.line_discount.paise > 0 && (
                                                    <div className="text-xs text-emerald-600 dark:text-emerald-400">
                                                        Saved {line.line_discount.formatted}
                                                    </div>
                                                )}
                                            </div>

                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                onClick={() => handleRemoveItem(line.product_id)}
                                                className="text-muted-foreground hover:text-destructive"
                                            >
                                                Remove
                                            </Button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="space-y-6 lg:col-span-1">
                            {/* Order Summary */}
                            <div className="space-y-4 rounded-2xl border bg-card p-5 shadow-sm">
                                <h2 className="font-semibold text-lg">Order Summary</h2>

                                <div className="space-y-2 text-sm">
                                    <div className="flex justify-between text-muted-foreground">
                                        <span>Subtotal</span>
                                        <span className="font-medium text-foreground">{breakdown.subtotal.formatted}</span>
                                    </div>

                                    {breakdown.member_discount.paise > 0 && (
                                        <div className="flex justify-between text-emerald-600 dark:text-emerald-400">
                                            <span>Member Discount (15%)</span>
                                            <span className="font-medium">-{breakdown.member_discount.formatted}</span>
                                        </div>
                                    )}

                                    {breakdown.coupon_discount.paise > 0 && (
                                        <div className="flex justify-between text-emerald-600 dark:text-emerald-400">
                                            <span>Coupon ({breakdown.coupon?.code})</span>
                                            <span className="font-medium">-{breakdown.coupon_discount.formatted}</span>
                                        </div>
                                    )}

                                    {breakdown.tax.paise > 0 && (
                                        <div className="flex justify-between text-muted-foreground">
                                            <span>Tax</span>
                                            <span className="font-medium text-foreground">{breakdown.tax.formatted}</span>
                                        </div>
                                    )}

                                    <div className="border-t pt-3 flex justify-between text-base font-bold">
                                        <span>Total Payable</span>
                                        <span className="text-emerald-600 dark:text-emerald-400">{breakdown.total.formatted}</span>
                                    </div>
                                </div>

                                <Button asChild className="w-full size-lg text-base">
                                    <a href={checkoutShowRoute.url()}>Proceed to Checkout</a>
                                </Button>
                            </div>

                            {/* Coupon Form */}
                            <div className="space-y-3 rounded-2xl border bg-card p-5 shadow-sm">
                                <h3 className="font-semibold text-sm">Have a coupon code?</h3>

                                {breakdown.coupon ? (
                                    <div className="flex items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm dark:border-emerald-900/50 dark:bg-emerald-950/40">
                                        <div className="font-mono font-bold text-emerald-700 dark:text-emerald-300">
                                            {breakdown.coupon.code}
                                        </div>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            onClick={handleRemoveCoupon}
                                            className="text-xs text-muted-foreground hover:text-destructive"
                                        >
                                            Remove
                                        </Button>
                                    </div>
                                ) : (
                                    <form onSubmit={handleApplyCoupon} className="space-y-2">
                                        <div className="flex gap-2">
                                            <input
                                                type="text"
                                                name="code"
                                                value={couponForm.data.code}
                                                onChange={(e) => couponForm.setData('code', e.target.value)}
                                                placeholder="COUPON2026"
                                                className="w-full rounded-md border px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-primary"
                                            />
                                            <Button type="submit" variant="outline" disabled={couponForm.processing}>
                                                Apply
                                            </Button>
                                        </div>
                                        {couponForm.errors.code && (
                                            <p className="text-xs text-destructive">{couponForm.errors.code}</p>
                                        )}
                                    </form>
                                )}
                            </div>
                        </div>
                    </div>
                ) : (
                    <div className="rounded-2xl border bg-card p-12 text-center shadow-sm space-y-4">
                        <div className="text-muted-foreground">Your cart is currently empty.</div>
                        <Button asChild>
                            <a href={shopIndexRoute.url()}>Browse Products</a>
                        </Button>
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
