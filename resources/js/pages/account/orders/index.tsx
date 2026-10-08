import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { show as orderShowRoute } from '@/routes/account/orders';
import { index as shopIndexRoute } from '@/routes/shop';

type MoneyProp = {
    paise: number;
    formatted: string;
};

type OrderSummary = {
    id: number;
    order_number: string;
    status: string;
    status_label: string;
    subtotal: MoneyProp;
    discount: MoneyProp;
    tax: MoneyProp;
    total: MoneyProp;
    created_at: string;
    items_count: number;
};

type PaginatedOrders = {
    data: OrderSummary[];
    current_page: number;
    last_page: number;
};

type Props = {
    orders: PaginatedOrders;
};

export default function OrderIndex({ orders }: Props) {
    return (
        <AppLayout>
            <Head title="Order History — BrightLearners" />

            <div className="mx-auto max-w-5xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-2xl font-bold tracking-tight">Order History</h1>
                    <p className="text-sm text-muted-foreground">
                        View past purchases and receipt details for your account.
                    </p>
                </header>

                {orders.data.length > 0 ? (
                    <div className="divide-y rounded-2xl border bg-card shadow-sm">
                        {orders.data.map((order) => (
                            <div
                                key={order.id}
                                className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5"
                            >
                                <div className="space-y-1">
                                    <div className="flex items-center gap-3">
                                        <span className="font-semibold text-base">{order.order_number}</span>
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
                                    <div className="text-xs text-muted-foreground">
                                        Placed on {order.created_at} • {order.items_count} item(s)
                                    </div>
                                </div>

                                <div className="flex items-center justify-between sm:justify-end gap-6">
                                    <div className="text-right font-semibold text-base">
                                        {order.total.formatted}
                                    </div>

                                    <Button asChild variant="outline" size="sm">
                                        <a href={orderShowRoute.url({ order: order.id })}>
                                            View Details
                                        </a>
                                    </Button>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="rounded-2xl border bg-card p-12 text-center shadow-sm space-y-4">
                        <div className="text-muted-foreground">You haven't placed any orders yet.</div>
                        <Button asChild>
                            <a href={shopIndexRoute.url()}>Explore Shop</a>
                        </Button>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
