import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    Calendar,
    CheckCircle2,
    Clock,
    Percent,
    Sparkles,
    ArrowRight,
} from 'lucide-react';

type ActiveSubscription = {
    id: number;
    plan_name: string;
    plan_code: string;
    status: string;
    status_label: string;
    starts_at: string;
    ends_at: string;
    days_remaining: number;
    auto_renew: boolean;
    member_discount_percent: number;
    inclusions: string | null;
};

type HistoryItem = {
    id: number;
    plan_name: string;
    plan_code: string;
    status: string;
    status_label: string;
    starts_at: string;
    ends_at: string;
    is_active: boolean;
    order_number: string | null;
};

type Props = {
    active_subscription: ActiveSubscription | null;
    history: HistoryItem[];
};

export default function AccountMembership({
    active_subscription,
    history,
}: Props) {
    return (
        <AppLayout>
            <Head title="Membership — BrightLearners" />

            <div className="mx-auto max-w-4xl space-y-8 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-3xl font-bold tracking-tight">
                        Membership
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Manage your family programme access, view active perks,
                        and track subscription terms.
                    </p>
                </header>

                {/* Active Membership Status */}
                {active_subscription ? (
                    <div className="space-y-6 rounded-3xl border border-primary/20 bg-card p-6 shadow-sm sm:p-8">
                        <div className="flex flex-col justify-between gap-4 border-b pb-6 sm:flex-row sm:items-center">
                            <div className="space-y-1">
                                <div className="flex items-center gap-2">
                                    <span className="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        <CheckCircle2 className="h-3.5 w-3.5" />{' '}
                                        Active Programme
                                    </span>
                                    <span className="font-mono text-xs text-muted-foreground">
                                        {active_subscription.plan_code}
                                    </span>
                                </div>
                                <h2 className="text-2xl font-bold">
                                    {active_subscription.plan_name}
                                </h2>
                            </div>

                            <Button asChild size="sm">
                                <a href="/account/weekly-learning">
                                    View Weekly Learning{' '}
                                    <ArrowRight className="ml-1.5 h-4 w-4" />
                                </a>
                            </Button>
                        </div>

                        {/* Stats / Timing */}
                        <div className="grid gap-4 sm:grid-cols-3">
                            <div className="space-y-1 rounded-2xl border bg-muted/30 p-4">
                                <div className="flex items-center gap-1.5 text-xs text-muted-foreground">
                                    <Clock className="h-4 w-4 text-primary" />{' '}
                                    Days Remaining
                                </div>
                                <div className="text-2xl font-black text-foreground">
                                    {active_subscription.days_remaining} Days
                                </div>
                                <div className="text-[11px] text-muted-foreground">
                                    Expires on {active_subscription.ends_at}
                                </div>
                            </div>

                            <div className="space-y-1 rounded-2xl border bg-muted/30 p-4">
                                <div className="flex items-center gap-1.5 text-xs text-muted-foreground">
                                    <Calendar className="h-4 w-4 text-primary" />{' '}
                                    Active Term
                                </div>
                                <div className="text-sm font-semibold text-foreground">
                                    {active_subscription.starts_at}
                                </div>
                                <div className="text-[11px] text-muted-foreground">
                                    to {active_subscription.ends_at}
                                </div>
                            </div>

                            <div className="space-y-1 rounded-2xl border bg-muted/30 p-4">
                                <div className="flex items-center gap-1.5 text-xs text-muted-foreground">
                                    <Percent className="h-4 w-4 text-emerald-600" />{' '}
                                    Member Discount
                                </div>
                                <div className="text-2xl font-black text-emerald-600">
                                    {
                                        active_subscription.member_discount_percent
                                    }
                                    % Off
                                </div>
                                <div className="text-[11px] text-muted-foreground">
                                    Applied automatically in shop
                                </div>
                            </div>
                        </div>

                        {/* Inclusions & Policy */}
                        <div className="space-y-2 rounded-2xl bg-muted/40 p-4 text-xs text-muted-foreground">
                            <div className="flex items-center gap-1.5 font-semibold text-foreground">
                                <Sparkles className="h-4 w-4 text-primary" />{' '}
                                Pilot Programme Policy:
                            </div>
                            <p>
                                This 90-day pilot is a one-time prepaid period
                                without recurring charges. When your term
                                concludes, no automatic billing occurs. All PDF
                                worksheets downloaded during your active term
                                remain yours permanently.
                            </p>
                        </div>
                    </div>
                ) : (
                    <div className="space-y-4 rounded-3xl border bg-card p-8 text-center">
                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <Sparkles className="h-6 w-6" />
                        </div>
                        <div className="space-y-1">
                            <h2 className="text-xl font-bold">
                                No Active Membership
                            </h2>
                            <p className="mx-auto max-w-md text-sm text-muted-foreground">
                                You do not currently have an active weekly
                                learning subscription. Join our 90-day family
                                pilot to unlock weekly printable curriculum
                                packs and 15% storewide discounts!
                            </p>
                        </div>
                        <Button asChild size="lg" className="rounded-full px-8">
                            <a href="/membership">Explore 90-Day Pilot</a>
                        </Button>
                    </div>
                )}

                {/* Subscription History */}
                <div className="space-y-4">
                    <h2 className="text-lg font-bold">Membership History</h2>

                    {history.length === 0 ? (
                        <div className="rounded-2xl border bg-card p-6 text-center text-xs text-muted-foreground">
                            No past subscriptions found.
                        </div>
                    ) : (
                        <div className="overflow-hidden rounded-2xl border bg-card shadow-sm">
                            <div className="overflow-x-auto">
                                <table className="w-full text-left text-xs">
                                    <thead className="border-b bg-muted/50 text-[10px] tracking-wider text-muted-foreground uppercase">
                                        <tr>
                                            <th className="px-4 py-3">Plan</th>
                                            <th className="px-4 py-3">
                                                Period
                                            </th>
                                            <th className="px-4 py-3">
                                                Status
                                            </th>
                                            <th className="px-4 py-3">
                                                Order Ref
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-border">
                                        {history.map((item) => (
                                            <tr
                                                key={item.id}
                                                className="hover:bg-muted/20"
                                            >
                                                <td className="px-4 py-3 font-semibold text-foreground">
                                                    {item.plan_name}
                                                </td>
                                                <td className="px-4 py-3 text-muted-foreground">
                                                    {item.starts_at} —{' '}
                                                    {item.ends_at}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <span
                                                        className={`inline-flex rounded px-2 py-0.5 text-[11px] font-medium capitalize ${
                                                            item.is_active
                                                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300'
                                                                : 'bg-muted text-muted-foreground'
                                                        }`}
                                                    >
                                                        {item.status_label}
                                                    </span>
                                                </td>
                                                <td className="px-4 py-3 font-mono text-muted-foreground">
                                                    {item.order_number ?? '—'}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
