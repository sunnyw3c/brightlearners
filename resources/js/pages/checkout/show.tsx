import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { store as checkoutStoreRoute } from '@/routes/checkout';

type MoneyProp = {
    paise: number;
    formatted: string;
};

type BreakdownLine = {
    product_id: number;
    product_name: string;
    quantity: number;
    unit_price: MoneyProp;
    line_subtotal: MoneyProp;
    line_discount: MoneyProp;
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
    coupon: { code: string } | null;
    currency: string;
};

type Props = {
    breakdown: PriceBreakdownProp;
    user: {
        name: string;
        email: string;
    };
    notice?: string | null;
};

export default function CheckoutShow({ breakdown, user, notice }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        billing_name: user.name ?? '',
        billing_email: user.email ?? '',
        billing_phone: '',
        billing_state: '',
        terms: false,
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(checkoutStoreRoute.url());
    };

    return (
        <PublicLayout>
            <Head title="Checkout — BrightLearners" />

            <div className="mx-auto max-w-4xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-3xl font-bold tracking-tight">
                        Checkout
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Complete your billing information to place your order.
                    </p>
                </header>

                {notice && (
                    <div className="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                        {notice}
                    </div>
                )}

                <form
                    onSubmit={handleSubmit}
                    className="grid gap-8 lg:grid-cols-3"
                >
                    <div className="space-y-6 lg:col-span-2">
                        {/* Billing Details */}
                        <div className="space-y-4 rounded-2xl border bg-card p-6 shadow-sm">
                            <h2 className="text-lg font-semibold">
                                Billing Information
                            </h2>

                            <div className="space-y-4">
                                <div>
                                    <label className="mb-1 block text-sm font-medium">
                                        Full Name
                                    </label>
                                    <input
                                        type="text"
                                        value={data.billing_name}
                                        onChange={(e) =>
                                            setData(
                                                'billing_name',
                                                e.target.value,
                                            )
                                        }
                                        className="w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none"
                                        required
                                    />
                                    {errors.billing_name && (
                                        <p className="mt-1 text-xs text-destructive">
                                            {errors.billing_name}
                                        </p>
                                    )}
                                </div>

                                <div>
                                    <label className="mb-1 block text-sm font-medium">
                                        Email Address
                                    </label>
                                    <input
                                        type="email"
                                        value={data.billing_email}
                                        onChange={(e) =>
                                            setData(
                                                'billing_email',
                                                e.target.value,
                                            )
                                        }
                                        className="w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none"
                                        required
                                    />
                                    {errors.billing_email && (
                                        <p className="mt-1 text-xs text-destructive">
                                            {errors.billing_email}
                                        </p>
                                    )}
                                </div>

                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Phone Number (Optional)
                                        </label>
                                        <input
                                            type="tel"
                                            value={data.billing_phone}
                                            onChange={(e) =>
                                                setData(
                                                    'billing_phone',
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none"
                                            placeholder="+91 9876543210"
                                        />
                                        {errors.billing_phone && (
                                            <p className="mt-1 text-xs text-destructive">
                                                {errors.billing_phone}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            State (Optional)
                                        </label>
                                        <input
                                            type="text"
                                            value={data.billing_state}
                                            onChange={(e) =>
                                                setData(
                                                    'billing_state',
                                                    e.target.value,
                                                )
                                            }
                                            className="w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none"
                                            placeholder="Maharashtra"
                                        />
                                        {errors.billing_state && (
                                            <p className="mt-1 text-xs text-destructive">
                                                {errors.billing_state}
                                            </p>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Licence & Terms Agreement */}
                        <div className="space-y-4 rounded-2xl border bg-card p-6 shadow-sm">
                            <h2 className="text-lg font-semibold">
                                Terms & Conditions
                            </h2>

                            <label className="flex cursor-pointer items-start gap-3">
                                <input
                                    type="checkbox"
                                    checked={data.terms}
                                    onChange={(e) =>
                                        setData('terms', e.target.checked)
                                    }
                                    className="mt-1 rounded border-gray-300 text-primary focus:ring-primary"
                                    required
                                />
                                <span className="text-sm leading-relaxed text-muted-foreground">
                                    I agree to the digital delivery terms,
                                    household licence agreement, and refund
                                    policy. Digital items are granted
                                    immediately upon successful payment.
                                </span>
                            </label>
                            {errors.terms && (
                                <p className="mt-1 text-xs text-destructive">
                                    {errors.terms}
                                </p>
                            )}
                        </div>
                    </div>

                    {/* Order Summary Sidebar */}
                    <div className="space-y-6 lg:col-span-1">
                        <div className="space-y-4 rounded-2xl border bg-card p-5 shadow-sm">
                            <h2 className="text-lg font-semibold">
                                Order Items
                            </h2>

                            <div className="space-y-3 divide-y pt-2">
                                {breakdown.lines.map((line) => (
                                    <div
                                        key={line.product_id}
                                        className="flex justify-between pt-3 text-sm"
                                    >
                                        <div>
                                            <div className="font-medium">
                                                {line.product_name}
                                            </div>
                                            <div className="text-xs text-muted-foreground">
                                                Qty: {line.quantity}
                                            </div>
                                        </div>
                                        <div className="font-semibold">
                                            {line.line_total.formatted}
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="space-y-2 border-t pt-4 text-sm">
                                <div className="flex justify-between text-muted-foreground">
                                    <span>Subtotal</span>
                                    <span className="font-medium text-foreground">
                                        {breakdown.subtotal.formatted}
                                    </span>
                                </div>

                                {breakdown.discount.paise > 0 && (
                                    <div className="flex justify-between text-emerald-600 dark:text-emerald-400">
                                        <span>Discount</span>
                                        <span className="font-medium">
                                            -{breakdown.discount.formatted}
                                        </span>
                                    </div>
                                )}

                                {breakdown.tax.paise > 0 && (
                                    <div className="flex justify-between text-muted-foreground">
                                        <span>Tax</span>
                                        <span className="font-medium text-foreground">
                                            {breakdown.tax.formatted}
                                        </span>
                                    </div>
                                )}

                                <div className="flex justify-between border-t pt-3 text-base font-bold">
                                    <span>Total Amount</span>
                                    <span className="text-emerald-600 dark:text-emerald-400">
                                        {breakdown.total.formatted}
                                    </span>
                                </div>
                            </div>

                            <Button
                                type="submit"
                                disabled={processing}
                                className="size-lg mt-4 w-full text-base"
                            >
                                Place Order & Pay ({breakdown.total.formatted})
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </PublicLayout>
    );
}
