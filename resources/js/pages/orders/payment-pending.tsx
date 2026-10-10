import { useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Clock, RefreshCw } from 'lucide-react';

type MoneyProp = {
    paise: number;
    formatted: string;
};

type OrderPendingProp = {
    id: number;
    order_number: string;
    status: string;
    status_label: string;
    total: MoneyProp;
    created_at: string;
};

type Props = {
    order: OrderPendingProp;
};

export default function OrderPaymentPending({ order }: Props) {
    useEffect(() => {
        const interval = setInterval(() => {
            router.reload({ only: ['order'] });
        }, 5000);

        return () => clearInterval(interval);
    }, []);

    return (
        <AppLayout>
            <Head title={`Payment Pending — Order ${order.order_number}`} />

            <div className="mx-auto max-w-xl space-y-6 px-4 py-16 text-center">
                <div className="inline-flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                    <Clock className="h-12 w-12 animate-pulse" />
                </div>

                <div className="space-y-2">
                    <h1 className="text-3xl font-bold tracking-tight">
                        Payment Verification Pending
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        We are waiting for payment confirmation from Razorpay
                        for Order{' '}
                        <span className="font-semibold text-foreground">
                            #{order.order_number}
                        </span>
                        .
                    </p>
                </div>

                <div className="space-y-3 rounded-2xl border bg-card p-6 text-left text-sm">
                    <div className="flex justify-between">
                        <span className="text-muted-foreground">Status</span>
                        <span className="font-semibold text-amber-600 capitalize dark:text-amber-400">
                            {order.status_label}
                        </span>
                    </div>
                    <div className="flex justify-between">
                        <span className="text-muted-foreground">
                            Order Amount
                        </span>
                        <span className="font-semibold">
                            {order.total.formatted}
                        </span>
                    </div>
                </div>

                <div className="flex flex-col justify-center gap-3 pt-4 sm:flex-row">
                    <Button
                        onClick={() => router.reload()}
                        size="lg"
                        className="w-full sm:w-auto"
                    >
                        <RefreshCw className="mr-2 h-4 w-4" /> Check Status Now
                    </Button>
                    <Button
                        asChild
                        variant="outline"
                        size="lg"
                        className="w-full sm:w-auto"
                    >
                        <a href="/account/orders">My Orders</a>
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}
