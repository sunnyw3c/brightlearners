import { Form, Head, Link } from '@inertiajs/react';
import { ArrowRight, PackageOpen, ShieldCheck, Trash2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import PublicLayout from '@/layouts/public-layout';
import { show as checkoutShow } from '@/routes/checkout';
import {
    destroy as removeCoupon,
    store as applyCoupon,
} from '@/routes/cart/coupon';
import { destroy as removeItem } from '@/routes/cart/items';
import { index as shopIndex } from '@/routes/shop';
import type { PriceBreakdown } from '@/types/commerce';

type Props = {
    cart: { id: number; coupon: string | null; pricing: PriceBreakdown };
    notice: string | null;
};

function SummaryRow({
    label,
    value,
    strong = false,
}: {
    label: string;
    value: string;
    strong?: boolean;
}) {
    return (
        <div
            className={`flex items-center justify-between gap-4 ${strong ? 'text-lg font-black text-foreground' : 'text-sm text-muted-foreground'}`}
        >
            <span>{label}</span>
            <span
                className={
                    strong ? 'text-primary' : 'font-semibold text-foreground'
                }
            >
                {value}
            </span>
        </div>
    );
}

export default function CartShow({ cart, notice }: Props) {
    const { pricing } = cart;

    return (
        <PublicLayout>
            <Head title="Your Cart" />
            <div className="page-container py-8 sm:py-12">
                <div className="mb-7 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p className="mb-1 text-sm font-bold text-primary">
                            Your selection
                        </p>
                        <h1 className="section-title">
                            Your Cart ({pricing.lines.length}{' '}
                            {pricing.lines.length === 1 ? 'item' : 'items'})
                        </h1>
                    </div>
                    <Link
                        href={shopIndex()}
                        className="text-sm font-bold text-primary hover:underline"
                    >
                        Continue shopping
                    </Link>
                </div>

                {notice && (
                    <div className="mb-6 rounded-2xl border border-primary/15 bg-primary/5 px-5 py-4 text-sm font-medium text-foreground">
                        {notice}
                    </div>
                )}

                {pricing.lines.length === 0 ? (
                    <div className="surface-card flex min-h-80 flex-col items-center justify-center gap-4 p-8 text-center">
                        <span className="flex size-16 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                            <PackageOpen className="size-8" />
                        </span>
                        <div>
                            <h2 className="text-xl font-black">
                                Your cart is ready for something brilliant
                            </h2>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Choose a workbook or practice pack to get
                                started.
                            </p>
                        </div>
                        <Button asChild>
                            <Link href={shopIndex()}>Browse workbooks</Link>
                        </Button>
                    </div>
                ) : (
                    <div className="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_23rem]">
                        <section className="space-y-4">
                            {pricing.lines.map((line) => (
                                <article
                                    key={line.product_id}
                                    className="surface-card flex gap-4 p-4 sm:gap-6 sm:p-5"
                                >
                                    <div className="flex size-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-linear-to-br from-amber-50 to-sky-50 sm:size-32">
                                        {line.cover_image_url ? (
                                            <img
                                                src={line.cover_image_url}
                                                width={128}
                                                height={128}
                                                alt=""
                                                className="h-full w-full object-contain p-2"
                                            />
                                        ) : (
                                            <PackageOpen className="size-9 text-primary/45" />
                                        )}
                                    </div>
                                    <div className="flex min-w-0 flex-1 flex-col justify-between gap-4 py-1">
                                        <div>
                                            <p className="text-xs font-bold tracking-wide text-primary uppercase">
                                                {line.type.replaceAll('_', ' ')}
                                            </p>
                                            <h2 className="mt-1 text-base font-black sm:text-lg">
                                                {line.name}
                                            </h2>
                                            <p className="mt-1 text-xs text-muted-foreground">
                                                Digital PDF · Household licence
                                                · Instant access after payment
                                            </p>
                                        </div>
                                        <div className="flex items-center justify-between gap-3">
                                            <span className="text-lg font-black text-warm">
                                                {line.total.formatted}
                                            </span>
                                            <Form
                                                {...removeItem.form(
                                                    line.product_id,
                                                )}
                                            >
                                                {({ processing }) => (
                                                    <Button
                                                        type="submit"
                                                        size="sm"
                                                        variant="ghost"
                                                        disabled={processing}
                                                        className="text-muted-foreground hover:text-destructive"
                                                    >
                                                        <Trash2 className="size-4" />{' '}
                                                        Remove
                                                    </Button>
                                                )}
                                            </Form>
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </section>

                        <aside className="surface-card sticky top-20 space-y-5 p-5 sm:p-6">
                            <h2 className="text-lg font-black">
                                Order summary
                            </h2>
                            <div className="space-y-3">
                                <SummaryRow
                                    label="Subtotal"
                                    value={pricing.subtotal.formatted}
                                />
                                {pricing.discount.paise > 0 && (
                                    <SummaryRow
                                        label="Discount"
                                        value={`−${pricing.discount.formatted}`}
                                    />
                                )}
                                <SummaryRow
                                    label="Tax"
                                    value={
                                        pricing.tax.paise === 0
                                            ? '₹0'
                                            : pricing.tax.formatted
                                    }
                                />
                            </div>

                            <div className="border-t border-border pt-5">
                                {cart.coupon ? (
                                    <div className="flex items-center justify-between gap-3 rounded-xl bg-success/9 px-3 py-2.5 text-sm">
                                        <span>
                                            <strong>{cart.coupon}</strong>{' '}
                                            applied
                                        </span>
                                        <Form {...removeCoupon.form()}>
                                            {({ processing }) => (
                                                <button
                                                    type="submit"
                                                    disabled={processing}
                                                    className="font-bold text-primary hover:underline"
                                                >
                                                    Remove
                                                </button>
                                            )}
                                        </Form>
                                    </div>
                                ) : (
                                    <Form
                                        {...applyCoupon.form()}
                                        className="space-y-2"
                                    >
                                        {({ errors, processing }) => (
                                            <>
                                                <label
                                                    htmlFor="coupon"
                                                    className="text-xs font-bold text-foreground"
                                                >
                                                    Have a coupon code?
                                                </label>
                                                <div className="flex gap-2">
                                                    <Input
                                                        id="coupon"
                                                        name="coupon"
                                                        placeholder="Enter code"
                                                        className="uppercase"
                                                    />
                                                    <Button
                                                        type="submit"
                                                        variant="outline"
                                                        disabled={processing}
                                                    >
                                                        Apply
                                                    </Button>
                                                </div>
                                                {errors.coupon && (
                                                    <p className="text-xs font-medium text-destructive">
                                                        {errors.coupon}
                                                    </p>
                                                )}
                                            </>
                                        )}
                                    </Form>
                                )}
                            </div>

                            <div className="space-y-4 border-t border-border pt-5">
                                <SummaryRow
                                    label="Total"
                                    value={pricing.total.formatted}
                                    strong
                                />
                                <Button asChild size="lg" className="w-full">
                                    <Link href={checkoutShow()}>
                                        Proceed to checkout{' '}
                                        <ArrowRight className="size-4" />
                                    </Link>
                                </Button>
                                <p className="flex items-center justify-center gap-2 text-xs text-muted-foreground">
                                    <ShieldCheck className="size-4 text-success" />{' '}
                                    Secure, server-verified pricing
                                </p>
                            </div>
                        </aside>
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
