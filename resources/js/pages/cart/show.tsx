import { Head, useForm, router, Link } from '@inertiajs/react';
import { FormEvent } from 'react';
import { Minus, Plus, ShoppingBag, Trash2, ArrowRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import {
    store as couponStore,
    destroy as couponDestroy,
} from '@/routes/cart/coupon';
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
    const totalItemCount = breakdown.lines.reduce((acc, l) => acc + l.quantity, 0);

    return (
        <PublicLayout>
            <Head title="Your Cart — BrightLearners" />

            <div className="mx-auto max-w-5xl space-y-8 px-4 py-10">
                <header className="space-y-1">
                    <h1 className="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                        Your Cart ({hasItems ? `${totalItemCount} Items` : '0 Items'})
                    </h1>
                    <p className="text-sm font-medium text-slate-600 dark:text-slate-400">
                        Review your selected learning workbooks and resources.
                    </p>
                </header>

                {notice && (
                    <div className="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-medium text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                        {notice}
                    </div>
                )}

                {hasItems ? (
                    <div className="grid gap-10 lg:grid-cols-12">
                        {/* Cart Items List matching mockup */}
                        <div className="space-y-4 lg:col-span-7">
                            <div className="space-y-3">
                                {breakdown.lines.map((line) => (
                                    <div
                                        key={line.product_id}
                                        className="flex items-center justify-between gap-4 rounded-3xl border border-slate-200/80 bg-card p-4 shadow-2xs transition-all hover:shadow-xs dark:border-slate-800"
                                    >
                                        <div className="flex items-center gap-4">
                                            {/* Thumbnail Cover */}
                                            <div className="size-18 shrink-0 overflow-hidden rounded-2xl border border-slate-100 bg-amber-50/50 p-1 dark:border-slate-800">
                                                <img
                                                    src="/images/maths-workbook-3d.jpg"
                                                    alt={line.product_name}
                                                    className="h-full w-full object-contain"
                                                />
                                            </div>

                                            <div className="space-y-1">
                                                <h3 className="text-base font-bold text-foreground">
                                                    {line.product_name}
                                                </h3>
                                                <div className="text-base font-black text-[#EF4444]">
                                                    {line.unit_price.formatted}
                                                </div>
                                            </div>
                                        </div>

                                        <div className="flex items-center gap-3">
                                            {/* Quantity Stepper matching mockup (- 1 +) */}
                                            <div className="flex items-center rounded-full border border-slate-200 bg-white px-2 py-1 shadow-2xs dark:border-slate-800 dark:bg-slate-900">
                                                <button
                                                    type="button"
                                                    onClick={() => handleRemoveItem(line.product_id)}
                                                    className="flex size-7 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                                                    aria-label="Decrease quantity"
                                                >
                                                    <Minus className="size-3" />
                                                </button>
                                                <span className="w-6 text-center text-xs font-bold">
                                                    {line.quantity}
                                                </span>
                                                <button
                                                    type="button"
                                                    className="flex size-7 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800"
                                                    aria-label="Increase quantity"
                                                >
                                                    <Plus className="size-3" />
                                                </button>
                                            </div>

                                            <button
                                                type="button"
                                                onClick={() => handleRemoveItem(line.product_id)}
                                                className="flex size-8 items-center justify-center rounded-full text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors"
                                                aria-label="Remove item"
                                            >
                                                <Trash2 className="size-4" />
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Order Summary Sidebar matching mockup */}
                        <div className="space-y-6 lg:col-span-5">
                            {/* Coupon Code Input */}
                            <div className="rounded-3xl border border-slate-200/80 bg-card p-5 shadow-xs dark:border-slate-800">
                                <h3 className="text-sm font-bold text-foreground mb-3">
                                    Have a coupon code?
                                </h3>

                                {breakdown.coupon ? (
                                    <div className="flex items-center justify-between rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm dark:border-emerald-900/50 dark:bg-emerald-950/40">
                                        <div className="font-mono font-bold text-emerald-700 dark:text-emerald-300">
                                            {breakdown.coupon.code}
                                        </div>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            onClick={handleRemoveCoupon}
                                            className="h-7 text-xs text-rose-600 hover:text-rose-700"
                                        >
                                            Remove
                                        </Button>
                                    </div>
                                ) : (
                                    <form onSubmit={handleApplyCoupon} className="flex gap-2">
                                        <input
                                            type="text"
                                            placeholder="Enter code"
                                            value={couponForm.data.code}
                                            onChange={(e) => couponForm.setData('code', e.target.value)}
                                            className="h-11 flex-1 rounded-full border border-slate-200 bg-white px-4 text-sm font-medium focus:outline-hidden focus:ring-2 focus:ring-primary/20 dark:border-slate-800 dark:bg-slate-900"
                                        />
                                        <Button
                                            type="submit"
                                            disabled={couponForm.processing || !couponForm.data.code}
                                            className="h-11 rounded-full px-5 font-bold"
                                        >
                                            Apply
                                        </Button>
                                    </form>
                                )}
                            </div>

                            {/* Summary Card */}
                            <div className="space-y-4 rounded-3xl border border-slate-200/80 bg-card p-6 shadow-xs dark:border-slate-800">
                                <h2 className="text-lg font-bold tracking-tight text-foreground">
                                    Order Summary
                                </h2>

                                <div className="space-y-3 text-sm">
                                    <div className="flex justify-between text-slate-600 dark:text-slate-400">
                                        <span>Subtotal</span>
                                        <span className="font-bold text-foreground">
                                            {breakdown.subtotal.formatted}
                                        </span>
                                    </div>

                                    <div className="flex justify-between text-slate-600 dark:text-slate-400">
                                        <span>Discount</span>
                                        <span className="font-bold text-emerald-600">
                                            - {breakdown.discount.paise > 0 ? breakdown.discount.formatted : '₹0'}
                                        </span>
                                    </div>

                                    <div className="flex justify-between border-t border-slate-100 pt-3 text-lg font-black text-slate-900 dark:border-slate-800 dark:text-white">
                                        <span>Total</span>
                                        <span className="text-[#2563EB]">
                                            {breakdown.total.formatted}
                                        </span>
                                    </div>
                                </div>

                                <Button
                                    asChild
                                    size="lg"
                                    className="h-13 w-full rounded-full bg-[#2563EB] text-base font-bold text-white shadow-lg shadow-blue-500/25 hover:bg-blue-700 hover:shadow-xl transition-all"
                                >
                                    <a href={checkoutShowRoute.url()}>
                                        Proceed to Checkout
                                        <ArrowRight className="ml-2 size-4" />
                                    </a>
                                </Button>
                            </div>
                        </div>
                    </div>
                ) : (
                    <div className="rounded-3xl border border-dashed border-slate-300 p-12 text-center dark:border-slate-800">
                        <ShoppingBag className="mx-auto size-12 text-slate-400" />
                        <h2 className="mt-4 text-lg font-bold text-foreground">
                            Your cart is empty
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Explore our curated curriculum workbooks for Classes 1 to 3.
                        </p>
                        <div className="mt-6">
                            <Button asChild className="rounded-full bg-[#2563EB] font-bold text-white">
                                <Link href={shopIndexRoute()}>Browse Workbooks</Link>
                            </Button>
                        </div>
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
