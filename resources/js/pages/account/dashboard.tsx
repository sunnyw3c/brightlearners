import { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    Calendar,
    CheckCircle2,
    Clock,
    Download as DownloadIcon,
    FileText,
    Sparkles,
    UserPlus,
    ArrowRight,
    BookOpen,
    HelpCircle,
} from 'lucide-react';

type Profile = {
    id: number;
    nickname: string;
    class_id: number | null;
    class_name: string | null;
    avatar_key: string | null;
    is_active: boolean;
};

type WeekItem = {
    id: number;
    resource_id: number;
    title: string;
    slug: string;
    subject: string;
    type: string;
    type_label: string;
    guidance: string | null;
    required: boolean;
    current_version: string | null;
    download_url: string | null;
    library_url: string | null;
    progress_status: 'not_started' | 'opened' | 'downloaded' | 'completed';
    is_completed: boolean;
};

type Programme = {
    program_id: number;
    program_title: string;
    current_week_number: number;
    term_weeks_total: number;
    weeks_released: number;
    week_title: string | null;
    week_emphasis: string | null;
    items: WeekItem[];
};

type MonthlyProgress = {
    total_activities: number;
    completed_activities: number;
    opened_activities: number;
    percentage: number;
    label: string;
    opened_label: string;
};

type NextAction = {
    type: 'activity' | 'completed';
    title: string;
    description: string;
    item: WeekItem | null;
};

type NextSkillCheck = {
    title: string;
    date: string;
};

type RecentDownload = {
    id: number;
    resource_id: number;
    title: string;
    slug: string;
    type_label: string;
    downloaded_at: string;
    download_url: string;
};

type Props = {
    active_profile: Profile | null;
    profiles: Profile[];
    is_active_member: boolean;
    programme: Programme | null;
    monthly_progress: MonthlyProgress | null;
    next_release_date: string | null;
    next_skill_check: NextSkillCheck | null;
    next_action: NextAction | null;
    recent_downloads: RecentDownload[];
};

export default function Dashboard({
    active_profile,
    profiles,
    is_active_member,
    programme,
    monthly_progress,
    next_release_date,
    next_skill_check,
    next_action,
    recent_downloads,
}: Props) {
    const { auth } = usePage().props as { auth: { user: { name: string } } };
    const [processingId, setProcessingId] = useState<number | null>(null);

    const handleSwitchProfile = (profileId: number) => {
        router.post(`/account/profiles/${profileId}/switch`, {}, {
            preserveState: false,
            preserveScroll: true,
        });
    };

    const handleMarkComplete = (resourceId: number) => {
        setProcessingId(resourceId);
        router.post('/account/progress', {
            resource_id: resourceId,
            status: 'completed',
            profile_id: active_profile?.id,
        }, {
            preserveScroll: true,
            onFinish: () => setProcessingId(null),
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Dashboard', href: '/account/dashboard' }]}>
            <Head title="Parent Dashboard — BrightLearners" />

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                {/* Header & Child Profile Chips */}
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            Hello, {auth?.user?.name || 'Parent'}! 👋
                        </h1>
                        <p className="text-sm text-muted-foreground mt-1">
                            Your home base for weekly learning routines and practice progress.
                        </p>
                    </div>

                    {/* Child Profile Chips */}
                    <div className="flex flex-wrap items-center gap-2">
                        {profiles.map((p) => {
                            const isSelected = active_profile?.id === p.id;
                            return (
                                <button
                                    key={p.id}
                                    type="button"
                                    onClick={() => handleSwitchProfile(p.id)}
                                    className={`inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-medium transition-all ${
                                        isSelected
                                            ? 'bg-primary text-primary-foreground shadow-sm ring-2 ring-primary ring-offset-1'
                                            : 'bg-muted text-muted-foreground hover:bg-muted/80 hover:text-foreground'
                                    }`}
                                >
                                    <span>{p.nickname}</span>
                                    {p.class_name && (
                                        <span className={`text-[11px] ${isSelected ? 'text-primary-foreground/80' : 'text-muted-foreground'}`}>
                                            · {p.class_name}
                                        </span>
                                    )}
                                </button>
                            );
                        })}

                        <Button asChild variant="outline" size="sm" className="h-8 rounded-full text-xs gap-1">
                            <Link href="/account/profiles">
                                <UserPlus className="h-3.5 w-3.5" />
                                Add Child
                            </Link>
                        </Button>
                    </div>
                </div>

                {/* Empty State: No Child Profile */}
                {profiles.length === 0 && (
                    <div className="rounded-2xl border border-dashed border-border bg-card p-10 text-center shadow-sm">
                        <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <UserPlus className="h-7 w-7" />
                        </div>
                        <h2 className="mt-4 text-lg font-semibold">Add a Learning Profile to Get Started</h2>
                        <p className="mx-auto mt-2 max-w-md text-sm text-muted-foreground">
                            Create a child profile with their school class to access weekly structured learning packs, worksheets, and activity tracking.
                        </p>
                        <div className="mt-6 flex justify-center gap-3">
                            <Button asChild>
                                <Link href="/account/profiles">Add Child Profile</Link>
                            </Button>
                            <Button asChild variant="outline">
                                <Link href="/free">Explore Free Resources</Link>
                            </Button>
                        </div>
                    </div>
                )}

                {/* Empty State: Has Profile but No Active Membership */}
                {profiles.length > 0 && !is_active_member && (
                    <div className="rounded-2xl border bg-gradient-to-br from-primary/5 via-card to-card p-8 shadow-sm">
                        <div className="max-w-2xl space-y-4">
                            <span className="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                                <Sparkles className="h-3.5 w-3.5" /> Weekly Learning Programme
                            </span>
                            <h2 className="text-xl font-bold tracking-tight">
                                Unlock Weekly Structured Learning for {active_profile?.nickname || 'Your Child'}
                            </h2>
                            <p className="text-sm leading-relaxed text-muted-foreground">
                                Our 90-Day Weekly Programme delivers curated, print-friendly packs across Maths, English, and EVS every week—with full answer keys and guided routines to build consistent study habits.
                            </p>
                            <div className="flex flex-wrap gap-3 pt-2">
                                <Button asChild size="default">
                                    <Link href="/membership">
                                        Explore Membership <ArrowRight className="ml-1.5 h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button asChild variant="outline">
                                    <Link href="/membership/sample-month">View Sample Month</Link>
                                </Button>
                                <Button asChild variant="ghost">
                                    <Link href="/free">Browse Free Worksheets</Link>
                                </Button>
                            </div>
                        </div>
                    </div>
                )}

                {/* Active Membership Dashboard Content */}
                {profiles.length > 0 && is_active_member && programme && (
                    <div className="space-y-6">
                        {/* Weekly Overview & Progress Card */}
                        <div className="rounded-2xl border bg-card p-6 shadow-sm">
                            <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div className="space-y-1">
                                    <div className="flex items-center gap-2">
                                        <span className="rounded bg-primary/10 px-2 py-0.5 text-xs font-bold text-primary uppercase tracking-wider">
                                            Weekly Programme
                                        </span>
                                        <span className="text-xs text-muted-foreground font-medium">
                                            Week {programme.current_week_number} of {programme.term_weeks_total}
                                        </span>
                                    </div>
                                    <h2 className="text-xl font-bold tracking-tight">
                                        {programme.week_title || `Week ${programme.current_week_number}`}
                                    </h2>
                                    {programme.week_emphasis && (
                                        <p className="text-xs text-muted-foreground">
                                            Focus: {programme.week_emphasis}
                                        </p>
                                    )}
                                </div>

                                {/* Monthly Progress Bar (M-08 compliant) */}
                                {monthly_progress && (
                                    <div className="min-w-[240px] space-y-1.5 rounded-xl bg-muted/50 p-3.5 border border-border/50">
                                        <div className="flex items-center justify-between text-xs">
                                            <span className="font-medium text-foreground">Monthly Routine</span>
                                            <span className="text-muted-foreground font-semibold">
                                                {monthly_progress.percentage}%
                                            </span>
                                        </div>
                                        <div className="h-2 w-full overflow-hidden rounded-full bg-muted">
                                            <div
                                                className="h-full rounded-full bg-primary transition-all duration-500 ease-out"
                                                style={{ width: `${monthly_progress.percentage}%` }}
                                            />
                                        </div>
                                        <div className="flex justify-between text-[11px] text-muted-foreground">
                                            <span>{monthly_progress.label}</span>
                                            <span>{monthly_progress.opened_label}</span>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* One Clear Next Action (Step 11.2) */}
                        {next_action && next_action.item && (
                            <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 rounded-xl border border-primary/20 bg-primary/5 p-4 shadow-sm">
                                <div className="space-y-1">
                                    <div className="flex items-center gap-1.5 text-xs font-semibold text-primary">
                                        <Sparkles className="h-4 w-4" /> Recommended Next Action
                                    </div>
                                    <h3 className="font-semibold text-foreground text-sm sm:text-base">
                                        {next_action.item.title}
                                    </h3>
                                    <p className="text-xs text-muted-foreground">
                                        {next_action.description}
                                    </p>
                                </div>
                                <div className="flex items-center gap-2 shrink-0">
                                    {next_action.item.download_url && (
                                        <Button asChild size="sm">
                                            <a href={next_action.item.download_url} target="_blank" rel="noopener noreferrer">
                                                <DownloadIcon className="mr-1.5 h-3.5 w-3.5" /> Download
                                            </a>
                                        </Button>
                                    )}
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        disabled={processingId === next_action.item.resource_id}
                                        onClick={() => handleMarkComplete(next_action.item!.resource_id)}
                                    >
                                        <CheckCircle2 className="mr-1.5 h-3.5 w-3.5 text-emerald-600" />
                                        Mark Done
                                    </Button>
                                </div>
                            </div>
                        )}

                        {/* This Week's Activities Cards Grid */}
                        <div>
                            <div className="flex items-center justify-between pb-3">
                                <h3 className="text-base font-semibold tracking-tight">
                                    This Week's Learning Activities
                                </h3>
                                <Link
                                    href="/account/weekly-learning"
                                    className="text-xs font-medium text-primary hover:underline flex items-center gap-1"
                                >
                                    Full Programme Roadmap <ArrowRight className="h-3 w-3" />
                                </Link>
                            </div>

                            <div className="grid gap-4 md:grid-cols-3">
                                {programme.items.map((item) => (
                                    <div
                                        key={item.id}
                                        className="flex flex-col justify-between rounded-xl border bg-card p-4 shadow-sm hover:border-primary/40 transition-colors"
                                    >
                                        <div className="space-y-2.5">
                                            <div className="flex items-center justify-between gap-2">
                                                <span className="inline-flex items-center rounded-md bg-secondary px-2 py-0.5 text-xs font-semibold text-secondary-foreground">
                                                    {item.subject}
                                                </span>
                                                <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium capitalize ${
                                                    item.progress_status === 'completed'
                                                        ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300'
                                                        : item.progress_status === 'downloaded'
                                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-950/50 dark:text-blue-300'
                                                        : item.progress_status === 'opened'
                                                        ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300'
                                                        : 'bg-muted text-muted-foreground'
                                                }`}>
                                                    {item.progress_status.replace('_', ' ')}
                                                </span>
                                            </div>

                                            <h4 className="line-clamp-2 text-sm font-bold text-foreground">
                                                <a href={item.library_url || '#'} className="hover:underline">
                                                    {item.title}
                                                </a>
                                            </h4>

                                            {item.guidance && (
                                                <p className="line-clamp-2 text-xs text-muted-foreground">
                                                    {item.guidance}
                                                </p>
                                            )}
                                        </div>

                                        <div className="mt-4 flex items-center justify-between gap-2 border-t pt-3">
                                            {item.download_url ? (
                                                <Button asChild size="sm" variant="outline" className="h-8 text-xs">
                                                    <a href={item.download_url} target="_blank" rel="noopener noreferrer">
                                                        <DownloadIcon className="mr-1 h-3 w-3" /> Download
                                                    </a>
                                                </Button>
                                            ) : <div />}

                                            <Button
                                                size="sm"
                                                variant={item.is_completed ? 'secondary' : 'default'}
                                                className="h-8 text-xs"
                                                disabled={item.is_completed || processingId === item.resource_id}
                                                onClick={() => handleMarkComplete(item.resource_id)}
                                            >
                                                {item.is_completed ? (
                                                    <span className="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                        <CheckCircle2 className="h-3.5 w-3.5" /> Done
                                                    </span>
                                                ) : (
                                                    'Mark Complete'
                                                )}
                                            </Button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Release & Schedule Info (Step 11.6) */}
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div className="flex items-center gap-3.5 rounded-xl border bg-card p-4 shadow-sm">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <Calendar className="h-5 w-5" />
                                </div>
                                <div className="space-y-0.5">
                                    <div className="text-xs font-medium text-muted-foreground">Next Weekly Pack Drops</div>
                                    <div className="text-sm font-bold text-foreground">
                                        {next_release_date || 'Schedule in progress'}
                                    </div>
                                </div>
                            </div>

                            <div className="flex items-center gap-3.5 rounded-xl border bg-card p-4 shadow-sm">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-secondary text-secondary-foreground">
                                    <Clock className="h-5 w-5" />
                                </div>
                                <div className="space-y-0.5">
                                    <div className="text-xs font-medium text-muted-foreground">Upcoming Skill Check</div>
                                    <div className="text-sm font-bold text-foreground">
                                        {next_skill_check ? `${next_skill_check.title} (${next_skill_check.date})` : 'Monthly Review Pack'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Recent Downloads Section */}
                <div className="space-y-3">
                    <div className="flex items-center justify-between">
                        <h3 className="text-base font-semibold tracking-tight">Recent Downloads</h3>
                        <Link href="/account/library" className="text-xs font-medium text-primary hover:underline">
                            View all in library
                        </Link>
                    </div>

                    {recent_downloads.length === 0 ? (
                        <div className="rounded-xl border border-dashed p-6 text-center text-xs text-muted-foreground">
                            No recent downloads yet. Materials you download will appear here for quick access.
                        </div>
                    ) : (
                        <div className="divide-y rounded-xl border bg-card">
                            {recent_downloads.map((d) => (
                                <div key={d.id} className="flex items-center justify-between p-3.5">
                                    <div className="flex items-center gap-3">
                                        <FileText className="h-4 w-4 text-muted-foreground shrink-0" />
                                        <div>
                                            <a
                                                href={`/account/library/${d.slug}`}
                                                className="text-xs font-medium hover:underline text-foreground"
                                            >
                                                {d.title}
                                            </a>
                                            <div className="text-[11px] text-muted-foreground">
                                                {d.type_label} · {d.downloaded_at}
                                            </div>
                                        </div>
                                    </div>
                                    {d.download_url && (
                                        <Button asChild size="sm" variant="ghost" className="h-7 text-xs">
                                            <a href={d.download_url} target="_blank" rel="noopener noreferrer">
                                                <DownloadIcon className="h-3 w-3" />
                                            </a>
                                        </Button>
                                    )}
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
