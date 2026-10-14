import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    AlertCircle,
    Calendar,
    CheckCircle2,
    CreditCard,
    Info,
    ShieldAlert,
    Sparkles,
    XCircle,
} from 'lucide-react';

type SubscriptionData = {
    id: number;
    plan_name: string;
    plan_code: string;
    price_formatted: string | null;
    status: string;
    is_active: boolean;
    auto_renew: boolean;
    cancel_at_period_end: boolean;
    starts_at: string;
    ends_at: string;
    cancelled_at: string | null;
    grace_ends_at: string | null;
    is_in_grace: boolean;
};

type Props = {
    subscription: SubscriptionData | null;
};

export default function MembershipManage({ subscription }: Props) {
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [cancelReason, setCancelReason] = useState('');
    const [submitting, setSubmitting] = useState(false);

    const handleConfirmCancel = (e: React.FormEvent) => {
        e.preventDefault();
        setSubmitting(true);
        router.post(
            '/membership/manage/cancel',
            { reason: cancelReason },
            {
                onFinish: () => {
                    setSubmitting(false);
                    setShowCancelModal(false);
                },
            },
        );
    };

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Manage Membership', href: '/membership/manage' },
            ]}
        >
            <Head title="Manage Membership — BrightLearners" />

            <div className="mx-auto max-w-4xl space-y-8 px-4 py-8">
                {/* Header */}
                <div>
                    <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                        Membership & Recurring Billing
                    </h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        View current billing period, renewal settings, or cancel
                        your active plan anytime.
                    </p>
                </div>

                {subscription ? (
                    <div className="space-y-6">
                        {/* Grace Period Warning if payment failed */}
                        {subscription.is_in_grace && (
                            <div className="flex items-start gap-3.5 rounded-2xl border border-amber-300 bg-amber-50 p-5 text-amber-950 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200">
                                <AlertCircle className="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400" />
                                <div className="space-y-1 text-xs leading-relaxed sm:text-sm">
                                    <div className="font-bold">
                                        Payment Renewal Notice
                                    </div>
                                    <p>
                                        Your recurring renewal payment could not
                                        be processed. You have a grace period
                                        until{' '}
                                        <span className="font-semibold">
                                            {subscription.grace_ends_at}
                                        </span>{' '}
                                        with uninterrupted access while Razorpay
                                        retries the card authorization.
                                    </p>
                                </div>
                            </div>
                        )}

                        {/* Plan Summary Card */}
                        <div className="space-y-6 rounded-3xl border bg-card p-6 shadow-sm sm:p-8">
                            <div className="flex flex-col gap-4 border-b pb-5 sm:flex-row sm:items-center sm:justify-between">
                                <div className="space-y-1">
                                    <div className="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">
                                        <Sparkles className="h-3.5 w-3.5" />
                                        {subscription.status.toUpperCase()}
                                    </div>
                                    <h2 className="text-2xl font-bold">
                                        {subscription.plan_name}
                                    </h2>
                                    {subscription.price_formatted && (
                                        <div className="text-base font-medium text-muted-foreground">
                                            {subscription.price_formatted} /
                                            billing period
                                        </div>
                                    )}
                                </div>

                                <div className="flex flex-col items-start gap-1 text-xs sm:items-end sm:text-sm">
                                    <span className="text-muted-foreground">
                                        Current Term Ends:
                                    </span>
                                    <span className="text-base font-bold text-foreground">
                                        {subscription.ends_at}
                                    </span>
                                </div>
                            </div>

                            {/* Details Grid */}
                            <div className="grid gap-4 text-sm sm:grid-cols-2">
                                <div className="space-y-1 rounded-2xl bg-muted/30 p-4">
                                    <div className="text-xs text-muted-foreground">
                                        Started On
                                    </div>
                                    <div className="font-semibold">
                                        {subscription.starts_at}
                                    </div>
                                </div>
                                <div className="space-y-1 rounded-2xl bg-muted/30 p-4">
                                    <div className="text-xs text-muted-foreground">
                                        Auto-Renewal
                                    </div>
                                    <div className="font-semibold">
                                        {subscription.cancel_at_period_end
                                            ? 'Cancels at end of period (no renewal)'
                                            : subscription.auto_renew
                                              ? 'Active (Renews automatically)'
                                              : 'Inactive'}
                                    </div>
                                </div>
                            </div>

                            {/* Cancellation Notice if already scheduled */}
                            {subscription.cancel_at_period_end ? (
                                <div className="rounded-2xl border border-blue-200 bg-blue-50/70 p-4 text-xs text-blue-900 sm:text-sm dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-200">
                                    <div className="font-bold">
                                        Cancellation Scheduled
                                    </div>
                                    <p className="mt-1">
                                        Your subscription will not renew. You
                                        retain full access to all weekly
                                        learning packs until{' '}
                                        <span className="font-semibold">
                                            {subscription.ends_at}
                                        </span>
                                        .
                                    </p>
                                </div>
                            ) : subscription.is_active ? (
                                <div className="flex items-center justify-between gap-4 border-t pt-5">
                                    <div className="max-w-md text-xs text-muted-foreground">
                                        You can cancel anytime. If you cancel,
                                        your access continues undisturbed until
                                        your paid period ends on{' '}
                                        {subscription.ends_at}.
                                    </div>
                                    <Button
                                        variant="outline"
                                        onClick={() => setShowCancelModal(true)}
                                        className="border-destructive/30 text-xs text-destructive hover:bg-destructive/10"
                                    >
                                        Cancel Subscription
                                    </Button>
                                </div>
                            ) : null}
                        </div>
                    </div>
                ) : (
                    <div className="space-y-4 rounded-3xl border border-dashed p-10 text-center">
                        <CreditCard className="mx-auto h-12 w-12 text-muted-foreground/60" />
                        <h2 className="text-lg font-bold">
                            No active recurring membership found
                        </h2>
                        <p className="mx-auto max-w-md text-sm text-muted-foreground">
                            Join the Weekly Learning Programme to unlock
                            structured packs and guided practice for Classes
                            1–3.
                        </p>
                        <div className="pt-2">
                            <Link href="/membership">
                                <Button className="gap-2">
                                    Explore Membership Plans
                                </Button>
                            </Link>
                        </div>
                    </div>
                )}

                {/* Cancel Confirmation Modal */}
                {showCancelModal && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
                        <div className="w-full max-w-lg animate-in space-y-5 rounded-3xl border bg-card p-6 shadow-2xl zoom-in-95 fade-in sm:p-8">
                            <div className="flex items-start gap-4">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-destructive/10 text-destructive">
                                    <ShieldAlert className="h-5 w-5" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-bold">
                                        Cancel Recurring Membership?
                                    </h3>
                                    <p className="mt-1 text-xs text-muted-foreground sm:text-sm">
                                        Per our transparent customer terms, you
                                        will keep full access to all materials
                                        until{' '}
                                        <span className="font-semibold text-foreground">
                                            {subscription?.ends_at}
                                        </span>
                                        .
                                    </p>
                                </div>
                            </div>

                            <form
                                onSubmit={handleConfirmCancel}
                                className="space-y-4"
                            >
                                <div className="space-y-2">
                                    <label className="text-xs font-semibold text-muted-foreground">
                                        Reason for cancelling (optional):
                                    </label>
                                    <textarea
                                        value={cancelReason}
                                        onChange={(e) =>
                                            setCancelReason(e.target.value)
                                        }
                                        rows={3}
                                        placeholder="Help us understand how we can improve BrightLearners..."
                                        className="w-full rounded-xl border bg-background p-3 text-xs focus:ring-2 focus:ring-primary focus:outline-none sm:text-sm"
                                    />
                                </div>

                                <div className="flex justify-end gap-3 pt-2">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() =>
                                            setShowCancelModal(false)
                                        }
                                    >
                                        Keep Membership
                                    </Button>
                                    <Button
                                        type="submit"
                                        variant="destructive"
                                        disabled={submitting}
                                    >
                                        {submitting
                                            ? 'Cancelling...'
                                            : 'Confirm Cancellation'}
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
