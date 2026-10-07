import { Head, Link } from '@inertiajs/react';
import { PackageCheck } from 'lucide-react';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { show as orderShow } from '@/routes/account/orders';
import { index as shopIndex } from '@/routes/shop';
import type { Money } from '@/types/commerce';

type OrderSummary = {
    id: number;
    order_number: string;
    status: string;
    total: Money;
    items_count: number;
    created_at: string | null;
};
type Props = { orders: { data: OrderSummary[] } };

export default function OrdersIndex({ orders }: Props) {
    return (
        <PublicLayout>
            <Head title="Your Orders" />
            <div className="page-container py-8 sm:py-12">
                <div className="mb-8">
                    <p className="mb-1 text-sm font-bold text-primary">
                        Your account
                    </p>
                    <h1 className="section-title">Orders</h1>
                </div>
                {orders.data.length === 0 ? (
                    <div className="surface-card flex min-h-72 flex-col items-center justify-center gap-4 p-8 text-center">
                        <PackageCheck className="size-12 text-primary" />
                        <div>
                            <h2 className="text-xl font-black">
                                No orders yet
                            </h2>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Your purchases will appear here.
                            </p>
                        </div>
                        <Button asChild>
                            <Link href={shopIndex()}>Browse workbooks</Link>
                        </Button>
                    </div>
                ) : (
                    <div className="grid gap-4">
                        {orders.data.map((order) => (
                            <Link
                                key={order.id}
                                href={orderShow(order.id)}
                                className="surface-card flex flex-wrap items-center justify-between gap-4 p-5 transition hover:-translate-y-0.5 hover:border-primary/25"
                            >
                                <div>
                                    <p className="font-black">
                                        {order.order_number}
                                    </p>
                                    <p className="mt-1 text-xs text-muted-foreground">
                                        {order.created_at} · {order.items_count}{' '}
                                        {order.items_count === 1
                                            ? 'item'
                                            : 'items'}
                                    </p>
                                </div>
                                <div className="text-right">
                                    <span className="rounded-full bg-primary/9 px-3 py-1 text-xs font-bold text-primary capitalize">
                                        {order.status.replaceAll('_', ' ')}
                                    </span>
                                    <p className="mt-2 font-black">
                                        {order.total.formatted}
                                    </p>
                                </div>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
