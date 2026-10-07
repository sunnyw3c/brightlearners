import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft, LockKeyhole, ShieldCheck } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import PublicLayout from '@/layouts/public-layout';
import { show as cartShow } from '@/routes/cart';
import { store as checkoutStore } from '@/routes/checkout';
import type { PriceBreakdown } from '@/types/commerce';

type Props = {
    pricing: PriceBreakdown;
    billing: { name: string; email: string };
    notice: string | null;
};

export default function CheckoutShow({ pricing, billing, notice }: Props) {
    return (
        <PublicLayout>
            <Head title="Checkout" />
            <div className="page-container py-8 sm:py-12">
                <Link
                    href={cartShow()}
                    className="mb-5 inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline"
                >
                    <ArrowLeft className="size-4" /> Back to cart
                </Link>
                <div className="mb-8">
                    <p className="mb-1 text-sm font-bold text-primary">
                        Secure checkout
                    </p>
                    <h1 className="section-title">Complete your order</h1>
                    <p className="mt-2 text-sm text-muted-foreground">
                        Your downloads will be added to your BrightLearners
                        library after payment.
                    </p>
                </div>
                {notice && (
                    <div className="mb-6 rounded-2xl border border-primary/15 bg-primary/5 px-5 py-4 text-sm">
                        {notice}
                    </div>
                )}

                <Form
                    {...checkoutStore.form()}
                    className="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_23rem]"
                >
                    {({ errors, processing }) => (
                        <>
                            <section className="surface-card space-y-6 p-5 sm:p-7">
                                <div>
                                    <h2 className="text-xl font-black">
                                        Billing details
                                    </h2>
                                    <p className="mt-1 text-sm text-muted-foreground">
                                        Only the essentials for your receipt and
                                        account.
                                    </p>
                                </div>
                                <div className="grid gap-5 sm:grid-cols-2">
                                    <label className="space-y-2 text-sm font-bold">
                                        Billing name
                                        <Input
                                            name="billing_name"
                                            defaultValue={billing.name}
                                            autoComplete="name"
                                        />
                                        {errors.billing_name && (
                                            <span className="block text-xs text-destructive">
                                                {errors.billing_name}
                                            </span>
                                        )}
                                    </label>
                                    <label className="space-y-2 text-sm font-bold">
                                        Email
                                        <Input
                                            type="email"
                                            name="billing_email"
                                            defaultValue={billing.email}
                                            autoComplete="email"
                                        />
                                        {errors.billing_email && (
                                            <span className="block text-xs text-destructive">
                                                {errors.billing_email}
                                            </span>
                                        )}
                                    </label>
                                    <label className="space-y-2 text-sm font-bold sm:col-span-2">
                                        Phone{' '}
                                        <span className="font-normal text-muted-foreground">
                                            (optional)
                                        </span>
                                        <Input
                                            name="billing_phone"
                                            autoComplete="tel"
                                            placeholder="For payment support only"
                                        />
                                        {errors.billing_phone && (
                                            <span className="block text-xs text-destructive">
                                                {errors.billing_phone}
                                            </span>
                                        )}
                                    </label>
                                </div>

                                <label className="flex items-start gap-3 rounded-2xl border border-border bg-muted/35 p-4 text-sm leading-6">
                                    <input
                                        type="checkbox"
                                        name="terms_accepted"
                                        value="1"
                                        className="mt-1 size-4 rounded border-input accent-primary"
                                    />
                                    <span>
                                        I accept the{' '}
                                        <a
                                            href="#licence-terms"
                                            className="font-bold text-primary hover:underline"
                                        >
                                            household licence
                                        </a>{' '}
                                        and{' '}
                                        <a
                                            href="#refund-terms"
                                            className="font-bold text-primary hover:underline"
                                        >
                                            digital refund terms
                                        </a>
                                        .
                                    </span>
                                </label>
                                {errors.terms_accepted && (
                                    <p className="text-sm font-medium text-destructive">
                                        {errors.terms_accepted}
                                    </p>
                                )}

                                <div className="grid gap-3 text-sm text-muted-foreground sm:grid-cols-2">
                                    <div
                                        id="licence-terms"
                                        className="rounded-2xl border border-border p-4"
                                    >
                                        <strong className="block text-foreground">
                                            Household licence
                                        </strong>
                                        For use by one purchasing household.
                                        Files may not be redistributed or
                                        resold.
                                    </div>
                                    <div
                                        id="refund-terms"
                                        className="rounded-2xl border border-border p-4"
                                    >
                                        <strong className="block text-foreground">
                                            Digital refund terms
                                        </strong>
                                        Contact support if a file is defective.
                                        Access begins after verified payment.
                                    </div>
                                </div>
                            </section>

                            <aside className="surface-card sticky top-20 space-y-5 p-5 sm:p-6">
                                <h2 className="text-lg font-black">
                                    Order summary
                                </h2>
                                <div className="space-y-4">
                                    {pricing.lines.map((line) => (
                                        <div
                                            key={line.product_id}
                                            className="flex justify-between gap-3 text-sm"
                                        >
                                            <span className="min-w-0 font-semibold">
                                                {line.name}
                                            </span>
                                            <span className="shrink-0 font-bold">
                                                {line.total.formatted}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                                <div className="space-y-2 border-t border-border pt-4 text-sm">
                                    <div className="flex justify-between text-muted-foreground">
                                        <span>Subtotal</span>
                                        <span>
                                            {pricing.subtotal.formatted}
                                        </span>
                                    </div>
                                    {pricing.discount.paise > 0 && (
                                        <div className="flex justify-between text-success">
                                            <span>Discount</span>
                                            <span>
                                                −{pricing.discount.formatted}
                                            </span>
                                        </div>
                                    )}
                                    <div className="flex justify-between text-muted-foreground">
                                        <span>Tax</span>
                                        <span>{pricing.tax.formatted}</span>
                                    </div>
                                </div>
                                <div className="flex justify-between border-t border-border pt-4 text-lg font-black">
                                    <span>Total</span>
                                    <span className="text-primary">
                                        {pricing.total.formatted}
                                    </span>
                                </div>
                                {errors.cart && (
                                    <p className="text-sm font-medium text-destructive">
                                        {errors.cart}
                                    </p>
                                )}
                                {errors.coupon && (
                                    <p className="text-sm font-medium text-destructive">
                                        {errors.coupon}
                                    </p>
                                )}
                                <Button
                                    type="submit"
                                    size="lg"
                                    className="w-full"
                                    disabled={processing}
                                >
                                    <LockKeyhole className="size-4" />{' '}
                                    {processing
                                        ? 'Preparing order…'
                                        : `Continue securely · ${pricing.total.formatted}`}
                                </Button>
                                <p className="flex items-center justify-center gap-2 text-xs text-muted-foreground">
                                    <ShieldCheck className="size-4 text-success" />{' '}
                                    Amount verified on the server
                                </p>
                            </aside>
                        </>
                    )}
                </Form>
            </div>
        </PublicLayout>
    );
}
