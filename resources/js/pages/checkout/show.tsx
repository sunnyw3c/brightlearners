import { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import {
    CreditCard,
    Lock,
    ShieldCheck,
    Smartphone,
    Building2,
    CheckCircle2,
} from 'lucide-react';
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
    const [selectedMethod, setSelectedMethod] = useState<
        'razorpay' | 'card' | 'upi' | 'netbanking'
    >('razorpay');

    const { data, setData, post, processing, errors } = useForm({
        billing_name: user.name ?? '',
        billing_email: user.email ?? '',
        billing_phone: '',
        billing_state: '',
        terms: true,
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(checkoutStoreRoute.url());
    };

    return (
        <PublicLayout>
            <Head title="Secure Payment" />

            <div className="mx-auto max-w-4xl space-y-8 px-4 py-10">
                <header className="flex items-center gap-3">
                    <span className="flex size-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                        <Lock className="size-5" />
                    </span>
                    <div>
                        <h1 className="text-3xl font-black tracking-tight text-slate-900 dark:text-white">
                            Secure Payment
                        </h1>
                        <p className="text-sm font-medium text-slate-600 dark:text-slate-400">
                            Safe and encrypted checkout powered by Razorpay.
                        </p>
                    </div>
                </header>

                {notice && (
                    <div className="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-medium text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                        {notice}
                    </div>
                )}

                <form
                    onSubmit={handleSubmit}
                    className="grid gap-10 lg:grid-cols-12"
                >
                    <div className="space-y-6 lg:col-span-7">
                        {/* Payment Methods Selection matching mockup screen 8 */}
                        <div className="space-y-4 rounded-3xl border border-slate-200/80 bg-card p-6 shadow-xs dark:border-slate-800">
                            <h2 className="text-base font-bold text-foreground">
                                Select Payment Method
                            </h2>

                            <div className="space-y-3">
                                {/* Razorpay Option */}
                                <label
                                    onClick={() =>
                                        setSelectedMethod('razorpay')
                                    }
                                    className={`flex cursor-pointer items-center justify-between rounded-2xl border p-4 transition-all ${
                                        selectedMethod === 'razorpay'
                                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                            : 'border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900'
                                    }`}
                                >
                                    <div className="flex items-center gap-3.5">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            checked={
                                                selectedMethod === 'razorpay'
                                            }
                                            onChange={() =>
                                                setSelectedMethod('razorpay')
                                            }
                                            className="size-4 text-primary focus:ring-primary"
                                        />
                                        <div>
                                            <p className="text-sm font-bold text-foreground">
                                                Razorpay
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                UPI, Cards, Net Banking
                                            </p>
                                        </div>
                                    </div>
                                    <span className="text-sm font-bold tracking-tight text-blue-700 italic">
                                        Razorpay
                                    </span>
                                </label>

                                {/* Credit / Debit Card Option */}
                                <label
                                    onClick={() => setSelectedMethod('card')}
                                    className={`flex cursor-pointer items-center justify-between rounded-2xl border p-4 transition-all ${
                                        selectedMethod === 'card'
                                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                            : 'border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900'
                                    }`}
                                >
                                    <div className="flex items-center gap-3.5">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            checked={selectedMethod === 'card'}
                                            onChange={() =>
                                                setSelectedMethod('card')
                                            }
                                            className="size-4 text-primary focus:ring-primary"
                                        />
                                        <div>
                                            <p className="text-sm font-bold text-foreground">
                                                Credit / Debit Card
                                            </p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-1.5 text-xs font-bold text-slate-600">
                                        <span className="rounded bg-blue-900 px-1.5 py-0.5 text-[10px] text-white">
                                            VISA
                                        </span>
                                        <span className="rounded bg-amber-600 px-1.5 py-0.5 text-[10px] text-white">
                                            MC
                                        </span>
                                    </div>
                                </label>

                                {/* UPI Option */}
                                <label
                                    onClick={() => setSelectedMethod('upi')}
                                    className={`flex cursor-pointer items-center justify-between rounded-2xl border p-4 transition-all ${
                                        selectedMethod === 'upi'
                                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                            : 'border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900'
                                    }`}
                                >
                                    <div className="flex items-center gap-3.5">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            checked={selectedMethod === 'upi'}
                                            onChange={() =>
                                                setSelectedMethod('upi')
                                            }
                                            className="size-4 text-primary focus:ring-primary"
                                        />
                                        <div>
                                            <p className="text-sm font-bold text-foreground">
                                                UPI
                                            </p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-1.5 text-xs font-bold text-slate-600">
                                        <span className="rounded bg-sky-100 px-1.5 py-0.5 text-[10px] text-sky-800">
                                            GPay
                                        </span>
                                        <span className="rounded bg-purple-100 px-1.5 py-0.5 text-[10px] text-purple-800">
                                            PhonePe
                                        </span>
                                        <span className="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] text-blue-800">
                                            Paytm
                                        </span>
                                    </div>
                                </label>

                                {/* Net Banking Option */}
                                <label
                                    onClick={() =>
                                        setSelectedMethod('netbanking')
                                    }
                                    className={`flex cursor-pointer items-center justify-between rounded-2xl border p-4 transition-all ${
                                        selectedMethod === 'netbanking'
                                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                                            : 'border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900'
                                    }`}
                                >
                                    <div className="flex items-center gap-3.5">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            checked={
                                                selectedMethod === 'netbanking'
                                            }
                                            onChange={() =>
                                                setSelectedMethod('netbanking')
                                            }
                                            className="size-4 text-primary focus:ring-primary"
                                        />
                                        <div>
                                            <p className="text-sm font-bold text-foreground">
                                                Net Banking
                                            </p>
                                        </div>
                                    </div>
                                    <Building2 className="size-5 text-slate-500" />
                                </label>
                            </div>
                        </div>

                        {/* 100% Secure Guarantee Badge matching mockup */}
                        <div className="flex items-center gap-3.5 rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 dark:border-emerald-900/50 dark:bg-emerald-950/30">
                            <span className="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-xs">
                                <ShieldCheck className="size-6" />
                            </span>
                            <div>
                                <h3 className="text-sm font-bold text-emerald-950 dark:text-emerald-300">
                                    100% Secure Payment
                                </h3>
                                <p className="text-xs text-emerald-800 dark:text-emerald-400">
                                    Your payment information is encrypted and
                                    safe with Razorpay.
                                </p>
                            </div>
                        </div>

                        {/* Billing Information Form */}
                        <div className="space-y-4 rounded-3xl border border-slate-200/80 bg-card p-6 shadow-xs dark:border-slate-800">
                            <h2 className="text-base font-bold text-foreground">
                                Billing Contact
                            </h2>

                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label className="mb-1 block text-xs font-bold text-slate-700 dark:text-slate-300">
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
                                        className="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:outline-hidden dark:border-slate-800 dark:bg-slate-900"
                                        required
                                    />
                                    {errors.billing_name && (
                                        <p className="mt-1 text-xs text-destructive">
                                            {errors.billing_name}
                                        </p>
                                    )}
                                </div>

                                <div>
                                    <label className="mb-1 block text-xs font-bold text-slate-700 dark:text-slate-300">
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
                                        className="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:outline-hidden dark:border-slate-800 dark:bg-slate-900"
                                        required
                                    />
                                    {errors.billing_email && (
                                        <p className="mt-1 text-xs text-destructive">
                                            {errors.billing_email}
                                        </p>
                                    )}
                                </div>

                                <div>
                                    <label className="mb-1 block text-xs font-bold text-slate-700 dark:text-slate-300">
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
                                        className="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:outline-hidden dark:border-slate-800 dark:bg-slate-900"
                                        placeholder="+91 9876543210"
                                    />
                                </div>

                                <div>
                                    <label className="mb-1 block text-xs font-bold text-slate-700 dark:text-slate-300">
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
                                        className="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium focus:ring-2 focus:ring-primary/20 focus:outline-hidden dark:border-slate-800 dark:bg-slate-900"
                                        placeholder="Maharashtra"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Order Summary & Pay Button matching mockup */}
                    <div className="space-y-6 lg:col-span-5">
                        <div className="space-y-4 rounded-3xl border border-slate-200/80 bg-card p-6 shadow-xs dark:border-slate-800">
                            <h2 className="text-lg font-bold tracking-tight text-foreground">
                                Order Summary
                            </h2>

                            <div className="space-y-3 divide-y divide-slate-100 pt-2 dark:divide-slate-800">
                                {breakdown.lines.map((line) => (
                                    <div
                                        key={line.product_id}
                                        className="flex justify-between pt-3 text-sm"
                                    >
                                        <div>
                                            <div className="font-bold text-foreground">
                                                {line.product_name}
                                            </div>
                                            <div className="text-xs text-muted-foreground">
                                                Qty: {line.quantity}
                                            </div>
                                        </div>
                                        <div className="font-bold text-foreground">
                                            {line.line_total.formatted}
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="space-y-2 border-t border-slate-100 pt-4 text-sm dark:border-slate-800">
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

                                <div className="flex justify-between border-t border-slate-100 pt-3 text-lg font-black text-slate-900 dark:border-slate-800 dark:text-white">
                                    <span>Total</span>
                                    <span className="text-[#2563EB]">
                                        {breakdown.total.formatted}
                                    </span>
                                </div>
                            </div>

                            {/* Pay button matching mockup: Pay ₹348 */}
                            <Button
                                type="submit"
                                disabled={processing}
                                className="mt-4 h-13 w-full rounded-full bg-[#2563EB] text-base font-bold text-white shadow-lg shadow-blue-500/25 transition-all hover:bg-blue-700 hover:shadow-xl"
                            >
                                Pay {breakdown.total.formatted}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </PublicLayout>
    );
}
