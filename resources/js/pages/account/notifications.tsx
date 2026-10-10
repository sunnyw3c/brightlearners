import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    AlertCircle,
    Bell,
    Check,
    CheckCheck,
    Clock,
    ShoppingBag,
    Sparkles,
} from 'lucide-react';

type NotificationItem = {
    id: string;
    type: string;
    title: string;
    body: string;
    action_url: string | null;
    read_at: string | null;
    is_read: boolean;
    created_at: string;
};

type Props = {
    notifications: {
        data: NotificationItem[];
    };
    unread_count: number;
};

export default function Notifications({ notifications, unread_count }: Props) {
    const handleMarkAsRead = (id: string) => {
        router.post(`/account/notifications/${id}/read`, {}, {
            preserveScroll: true,
        });
    };

    const handleMarkAllRead = () => {
        router.post('/account/notifications/mark-all-read', {}, {
            preserveScroll: true,
        });
    };

    const getIcon = (type: string) => {
        switch (type) {
            case 'order_confirmation':
                return <ShoppingBag className="h-5 w-5 text-emerald-600 dark:text-emerald-400" />;
            case 'correction_notice':
                return <AlertCircle className="h-5 w-5 text-amber-600 dark:text-amber-400" />;
            case 'membership_expiring':
                return <Clock className="h-5 w-5 text-rose-600 dark:text-rose-400" />;
            case 'weekly_pack_released':
            case 'monthly_check_available':
            default:
                return <Sparkles className="h-5 w-5 text-primary" />;
        }
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Notifications', href: '/account/notifications' }]}>
            <Head title="Notifications — BrightLearners" />

            <div className="mx-auto max-w-4xl space-y-6 px-4 py-8">
                <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b pb-4">
                    <div className="flex items-center gap-3">
                        <h1 className="text-2xl font-bold tracking-tight">Notifications</h1>
                        {unread_count > 0 && (
                            <span className="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                {unread_count} unread
                            </span>
                        )}
                    </div>

                    {unread_count > 0 && (
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={handleMarkAllRead}
                            className="text-xs h-8 gap-1.5"
                        >
                            <CheckCheck className="h-3.5 w-3.5" />
                            Mark all as read
                        </Button>
                    )}
                </div>

                {notifications.data.length === 0 ? (
                    <div className="rounded-2xl border border-dashed p-12 text-center space-y-3">
                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
                            <Bell className="h-6 w-6" />
                        </div>
                        <h3 className="text-base font-semibold">All Caught Up!</h3>
                        <p className="mx-auto max-w-sm text-xs text-muted-foreground">
                            You have no new notifications right now. Important updates, weekly drops, and order receipts will be listed here.
                        </p>
                    </div>
                ) : (
                    <div className="divide-y rounded-2xl border bg-card shadow-sm">
                        {notifications.data.map((item) => (
                            <div
                                key={item.id}
                                className={`flex flex-col sm:flex-row sm:items-start justify-between gap-4 p-4 transition-colors ${
                                    !item.is_read ? 'bg-primary/[0.02]' : ''
                                }`}
                            >
                                <div className="flex items-start gap-3.5">
                                    <div className="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-muted">
                                        {getIcon(item.type)}
                                    </div>
                                    <div className="space-y-1">
                                        <div className="flex items-center gap-2">
                                            <h3 className={`text-sm font-semibold ${!item.is_read ? 'text-foreground' : 'text-foreground/90'}`}>
                                                {item.title}
                                            </h3>
                                            {!item.is_read && (
                                                <span className="h-2 w-2 rounded-full bg-primary" />
                                            )}
                                        </div>
                                        <p className="text-xs leading-relaxed text-muted-foreground">
                                            {item.body}
                                        </p>
                                        <div className="text-[11px] text-muted-foreground pt-1">
                                            {item.created_at}
                                        </div>
                                    </div>
                                </div>

                                <div className="flex items-center gap-2 self-end sm:self-center shrink-0">
                                    {item.action_url && (
                                        <Button asChild size="sm" variant="default" className="h-8 text-xs">
                                            <Link href={item.action_url}>
                                                View
                                            </Link>
                                        </Button>
                                    )}

                                    {!item.is_read && (
                                        <Button
                                            size="sm"
                                            variant="ghost"
                                            onClick={() => handleMarkAsRead(item.id)}
                                            className="h-8 text-xs gap-1 text-muted-foreground hover:text-foreground"
                                        >
                                            <Check className="h-3 w-3" /> Mark read
                                        </Button>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
