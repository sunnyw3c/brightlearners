import { useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { CheckCircle2, Clock } from 'lucide-react';

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
    total: MoneyProp;
};

type OrderSuccessProp = {
    id: number;
    order_number: string;
    status: string;
    status_label: string;
    is_paid: boolean;
    subtotal: MoneyProp;
    discount: MoneyProp;
    tax: MoneyProp;
    total: MoneyProp;
    billing_name: string;
    billing_email: string;
    created_at: string;
    items: OrderItemProp[];
};

type Props = {
    order: OrderSuccessProp;
};

export default function OrderSuccess({ order }: Props) {
    useEffect(() => {
        if (!order.is_paid) {
            const interval = setInterval(() => {
                router.reload({ only: ['order'] });
            }, 3000);

            return () => clearInterval(interval);
        }
    }, [order.is_paid]);

    return (
        <AppLayout>
            <Head title={`Order ${order.order_number} — BrightLearners`} />

            <div className="mx-auto max-w-3xl px-4 py-12">
                <div className="rounded-3xl border bg-card p-8 shadow-sm text-center space-y-6">
                    {order.is_paid ? (
                        <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                            <CheckCircle2 className="w-10 h-10" />
                        </div>
                    ) : (
                        <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400 animate-pulse">
                            <Clock className="w-10 h-10 animate-spin" />
                        </div>
                    )}

                    <div className="space-y-2">
                        <h1 className="text-3xl font-bold tracking-tight">
                            {order.is_paid ? 'Payment Successful!' : 'Confirming Your Payment...'}
                        </h1>
                        <p className="text-muted-foreground text-sm max-w-md mx-auto">
                            {order.is_paid
                                ? `Thank you for your order #${order.order_number}. A confirmation email has been sent to ${order.billing_email}.`
                                : `We are verifying your payment with Razorpay. This page will update automatically.`}
                        </p>
                    </div>

                    <div className="rounded-2xl border bg-muted/40 p-6 text-left space-y-4 text-sm">
                        <div className="flex justify-between items-center border-b pb-3">
                            <span className="text-muted-foreground">Order Number</span>
                            <span className="font-semibold">{order.order_number}</span>
                        </div>

                        <div className="space-y-2 divide-y divide-border">
                            {order.items.map((item) => (
                                <div key={item.id} className="pt-2 flex justify-between items-center">
                                    <div>
                                        <div className="font-medium">{item.product_name}</div>
                                        <div className="text-xs text-muted-foreground capitalize">{item.product_type.replace('_', ' ')}</div>
                                    </div>
                                    <div className="font-semibold">{item.total.formatted}</div>
                                </div>
                            ))}
                        </div>

                        <div className="border-t pt-3 flex justify-between font-bold text-base">
                            <span>Total Paid</span>
                            <span className="text-emerald-600 dark:text-emerald-400">{order.total.formatted}</span>
                        </div>
                    </div>

                    <div className="pt-4 flex flex-col sm:flex-row justify-center gap-3">
                        <Button asChild size="lg" className="w-full sm:w-auto">
                            <a href="/account/orders">View My Orders</a>
                        </Button>
                        <Button asChild variant="outline" size="lg" className="w-full sm:w-auto">
                            <a href="/shop">Continue Shopping</a>
                        </Button>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
