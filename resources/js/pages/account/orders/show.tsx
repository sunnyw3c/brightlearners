import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Clock3, ReceiptText } from 'lucide-react';
import PublicLayout from '@/layouts/public-layout';
import { index as ordersIndex } from '@/routes/account/orders';
import type { Money } from '@/types/commerce';

type OrderItem = {
    id: number;
    name: string;
    type: string;
    unit_price: Money;
    discount: Money;
    tax: Money;
    total: Money;
};
type Order = {
    id: number;
    order_number: string;
    status: string;
    subtotal: Money;
    discount: Money;
    tax: Money;
    total: Money;
    billing_name: string;
    billing_email: string;
    created_at: string | null;
    items: OrderItem[];
};

export default function OrderShow({ order }: { order: Order }) {
    return (
        <PublicLayout>
            <Head title={`Order ${order.order_number}`} />
            <div className="page-container py-8 sm:py-12">
                <Link
                    href={ordersIndex()}
                    className="mb-5 inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline"
                >
                    <ArrowLeft className="size-4" /> All orders
                </Link>
                <div className="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p className="mb-1 text-sm font-bold text-primary">
                            Order details
                        </p>
                        <h1 className="section-title">{order.order_number}</h1>
                        <p className="mt-2 text-sm text-muted-foreground">
                            Placed {order.created_at}
                        </p>
                    </div>
                    <span className="rounded-full bg-highlight/35 px-4 py-2 text-sm font-black text-highlight-foreground capitalize">
                        {order.status.replaceAll('_', ' ')}
                    </span>
                </div>
                <div className="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_23rem]">
                    <section className="surface-card divide-y divide-border p-5 sm:p-6">
                        <h2 className="pb-4 text-lg font-black">Items</h2>
                        {order.items.map((item) => (
                            <div
                                key={item.id}
                                className="flex justify-between gap-4 py-5"
                            >
                                <div>
                                    <p className="font-bold">{item.name}</p>
                                    <p className="mt-1 text-xs text-muted-foreground capitalize">
                                        {item.type.replaceAll('_', ' ')}
                                    </p>
                                </div>
                                <p className="font-black">
                                    {item.total.formatted}
                                </p>
                            </div>
                        ))}
                    </section>
                    <aside className="surface-card space-y-5 p-5 sm:p-6">
                        <div className="flex items-center gap-2">
                            <ReceiptText className="size-5 text-primary" />
                            <h2 className="text-lg font-black">Summary</h2>
                        </div>
                        <div className="space-y-3 text-sm">
                            <div className="flex justify-between text-muted-foreground">
                                <span>Subtotal</span>
                                <span>{order.subtotal.formatted}</span>
                            </div>
                            <div className="flex justify-between text-muted-foreground">
                                <span>Discount</span>
                                <span>−{order.discount.formatted}</span>
                            </div>
                            <div className="flex justify-between text-muted-foreground">
                                <span>Tax</span>
                                <span>{order.tax.formatted}</span>
                            </div>
                            <div className="flex justify-between border-t border-border pt-4 text-lg font-black">
                                <span>Total</span>
                                <span className="text-primary">
                                    {order.total.formatted}
                                </span>
                            </div>
                        </div>
                        {order.status === 'pending_payment' && (
                            <div className="rounded-2xl bg-highlight/20 p-4 text-sm">
                                <p className="flex items-center gap-2 font-bold">
                                    <Clock3 className="size-4" /> Payment
                                    pending
                                </p>
                                <p className="mt-1 text-muted-foreground">
                                    Razorpay payment is connected in Phase 8.
                                    Your verified order total is reserved.
                                </p>
                            </div>
                        )}
                        <div className="border-t border-border pt-4 text-sm">
                            <p className="font-bold">Billed to</p>
                            <p className="mt-1 text-muted-foreground">
                                {order.billing_name}
                                <br />
                                {order.billing_email}
                            </p>
                        </div>
                    </aside>
                </div>
            </div>
        </PublicLayout>
    );
}
