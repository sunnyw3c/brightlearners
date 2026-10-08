import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { index as orderIndexRoute } from '@/routes/account/orders';
import { CreditCard, Loader2 } from 'lucide-react';

type MoneyProp = {
    paise: number;
    formatted: string;
};

type OrderItemProp = {
    id: number;
    product_name: string;
    product_type: string;
    sku: string | null;
    unit_price: MoneyProp;
    quantity: number;
    discount: MoneyProp;
    tax: MoneyProp;
    total: MoneyProp;
};

type OrderDetailProp = {
    id: number;
    order_number: string;
    status: string;
    status_label: string;
    subtotal: MoneyProp;
    discount: MoneyProp;
    tax: MoneyProp;
    total: MoneyProp;
    coupon_code: string | null;
    billing_name: string;
    billing_email: string;
    billing_phone: string | null;
    billing_state: string | null;
    gstin: string | null;
    invoice_number: string | null;
    created_at: string;
    expires_at: string | null;
    items: OrderItemProp[];
};

type Props = {
    order: OrderDetailProp;
};

declare global {
    interface Window {
        Razorpay: any;
    }
}

export default function OrderShow({ order }: Props) {
    const [loadingPayment, setLoadingPayment] = useState(false);

    const handlePayNow = async () => {
        setLoadingPayment(true);
        try {
            const res = await fetch('/checkout/payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
                },
                body: JSON.stringify({ order_id: order.id }),
            });

            const data = await res.json();
            if (!res.ok) {
                alert(data.message || 'Could not initiate payment.');
                setLoadingPayment(false);
                return;
            }

            const paymentData = data.payment;

            // Load Razorpay JS script dynamically if not loaded
            if (typeof window.Razorpay === 'undefined') {
                await new Promise((resolve) => {
                    const script = document.createElement('script');
                    script.src = 'https://checkout.razorpay.com/v1/checkout.js';
                    script.onload = resolve;
                    document.body.appendChild(script);
                });
            }

            const options = {
                key: paymentData.key,
                amount: paymentData.amount,
                currency: paymentData.currency,
                name: paymentData.name,
                description: paymentData.description,
                order_id: paymentData.order_id,
                prefill: paymentData.prefill,
                handler: function (response: any) {
                    router.post('/payments/razorpay/callback', {
                        order_id: order.id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                    });
                },
                modal: {
                    ondismiss: function () {
                        setLoadingPayment(false);
                    },
                },
            };

            const rzp = new window.Razorpay(options);
            rzp.open();
        } catch (e: any) {
            alert(e.message || 'An error occurred during payment.');
            setLoadingPayment(false);
        }
    };

    return (
        <AppLayout>
            <Head title={`Order ${order.order_number} — BrightLearners`} />

            <div className="mx-auto max-w-4xl space-y-6 px-4 py-8">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-3">
                            <h1 className="text-2xl font-bold tracking-tight">{order.order_number}</h1>
                            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                order.status === 'paid'
                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300'
                                    : order.status === 'pending_payment'
                                    ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300'
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300'
                            }`}>
                                {order.status_label}
                            </span>
                        </div>
                        <p className="text-xs text-muted-foreground mt-1">
                            Placed on {order.created_at}
                        </p>
                    </div>

                    <Button asChild variant="outline" size="sm">
                        <a href={orderIndexRoute.url()}>← Back to Orders</a>
                    </Button>
                </div>

                <div className="grid gap-6 md:grid-cols-3">
                    {/* Items Purchased */}
                    <div className="space-y-4 md:col-span-2">
                        <div className="rounded-2xl border bg-card p-6 shadow-sm space-y-4">
                            <h2 className="font-semibold text-base">Purchased Items</h2>
                            <div className="divide-y">
                                {order.items.map((item) => (
                                    <div key={item.id} className="py-3 flex justify-between items-center gap-4">
                                        <div>
                                            <div className="font-medium text-sm">{item.product_name}</div>
                                            <div className="text-xs text-muted-foreground capitalize">
                                                {item.product_type.replace('_', ' ')} {item.sku ? `• ${item.sku}` : ''}
                                            </div>
                                        </div>
                                        <div className="text-right font-medium text-sm">
                                            {item.total.formatted}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Billing Information */}
                        <div className="rounded-2xl border bg-card p-6 shadow-sm space-y-3 text-sm">
                            <h2 className="font-semibold text-base">Billing Details</h2>
                            <div className="grid grid-cols-2 gap-2 text-muted-foreground">
                                <div>Name: <span className="font-medium text-foreground">{order.billing_name}</span></div>
                                <div>Email: <span className="font-medium text-foreground">{order.billing_email}</span></div>
                                {order.billing_phone && (
                                    <div>Phone: <span className="font-medium text-foreground">{order.billing_phone}</span></div>
                                )}
                                {order.billing_state && (
                                    <div>State: <span className="font-medium text-foreground">{order.billing_state}</span></div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Financial Summary */}
                    <div className="space-y-4 md:col-span-1">
                        <div className="rounded-2xl border bg-card p-5 shadow-sm space-y-3 text-sm">
                            <h2 className="font-semibold text-base">Financial Summary</h2>
                            <div className="space-y-2">
                                <div className="flex justify-between text-muted-foreground">
                                    <span>Subtotal</span>
                                    <span className="font-medium text-foreground">{order.subtotal.formatted}</span>
                                </div>

                                {order.discount.paise > 0 && (
                                    <div className="flex justify-between text-emerald-600 dark:text-emerald-400">
                                        <span>Discount {order.coupon_code ? `(${order.coupon_code})` : ''}</span>
                                        <span className="font-medium">-{order.discount.formatted}</span>
                                    </div>
                                )}

                                {order.tax.paise > 0 && (
                                    <div className="flex justify-between text-muted-foreground">
                                        <span>Tax</span>
                                        <span className="font-medium text-foreground">{order.tax.formatted}</span>
                                    </div>
                                )}

                                <div className="border-t pt-3 flex justify-between font-bold text-base">
                                    <span>Total Amount</span>
                                    <span className="text-emerald-600 dark:text-emerald-400">{order.total.formatted}</span>
                                </div>
                            </div>

                            {order.status === 'pending_payment' && (
                                <Button onClick={handlePayNow} disabled={loadingPayment} className="w-full mt-4" size="lg">
                                    {loadingPayment ? (
                                        <>
                                            <Loader2 className="mr-2 h-4 w-4 animate-spin" /> Processing...
                                        </>
                                    ) : (
                                        <>
                                            <CreditCard className="mr-2 h-4 w-4" /> Pay {order.total.formatted} Now
                                        </>
                                    )}
                                </Button>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
