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
    Play,
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

function getChildAvatar(nickname: string, index: number): string {
    const lower = nickname.toLowerCase();
    if (lower.includes('myra') || lower.includes('girl') || index % 2 === 1) {
        return '/images/avatar-myra.jpg';
    }
    return '/images/avatar-aarav.jpg';
}

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
        router.post(
            `/account/profiles/${profileId}/switch`,
            {},
            {
                preserveState: false,
                preserveScroll: true,
            },
        );
    };

    const handleMarkComplete = (resourceId: number) => {
        setProcessingId(resourceId);
        router.post(
            '/account/progress',
            {
                resource_id: resourceId,
                status: 'completed',
                profile_id: active_profile?.id,
            },
            {
                preserveScroll: true,
                onFinish: () => setProcessingId(null),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Dashboard', href: '/account/dashboard' }]}>
            <Head title="Parent Dashboard — BrightLearners" />

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                {/* Header & Child Profile Switcher Chips matching mockup */}
                <div className="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                            Hello, {auth?.user?.name || 'Priya'}! 👋
                        </h1>
                        <p className="mt-1 text-sm font-medium text-slate-600 dark:text-slate-400">
                            Here's your child's learning progress.
                        </p>
                    </div>

                    {/* Child Profile Chips with Avatars */}
                    <div className="flex flex-wrap items-center gap-2.5">
                        {profiles.map((p, idx) => {
                            const isSelected = active_profile?.id === p.id;
                            const avatarSrc = getChildAvatar(p.nickname, idx);

                            return (
                                <button
                                    key={p.id}
                                    type="button"
                                    onClick={() => handleSwitchProfile(p.id)}
                                    className={`inline-flex items-center gap-2 rounded-full py-1.5 pr-4 pl-1.5 text-xs font-bold transition-all shadow-xs ${
                                        isSelected
                                            ? 'bg-[#2563EB] text-white shadow-blue-500/25 ring-2 ring-blue-400/50'
                                            : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200'
                                    }`}
                                >
                                    <img
                                        src={avatarSrc}
                                        alt={p.nickname}
                                        className="size-6 rounded-full object-cover ring-1 ring-white/50"
                                    />
                                    <span>{p.nickname}</span>
                                    {p.class_name && (
                                        <span
                                            className={`text-[11px] font-medium ${
                                                isSelected
                                                    ? 'text-white/80'
                                                    : 'text-muted-foreground'
                                            }`}
                                        >
                                            · {p.class_name}
                                        </span>
                                    )}
                                </button>
                            );
                        })}

                        <Button
                            asChild
                            variant="outline"
                            size="sm"
                            className="h-9 rounded-full border-slate-200 text-xs font-bold gap-1.5 px-4 dark:border-slate-800"
                        >
                            <Link href="/account/profiles">
                                <UserPlus className="size-3.5" />
                                Add Child
                            </Link>
                        </Button>
                    </div>
                </div>

                {/* Empty State: No Child Profile */}
                {profiles.length === 0 && (
                    <div className="rounded-3xl border border-dashed border-slate-300 bg-card p-10 text-center shadow-xs dark:border-slate-700">
                        <div className="mx-auto flex size-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <UserPlus className="size-7" />
                        </div>
                        <h2 className="mt-4 text-lg font-bold">Add a Learning Profile to Get Started</h2>
                        <p className="mx-auto mt-2 max-w-md text-sm text-muted-foreground">
                            Create a child profile with their school class to access weekly structured learning packs, worksheets, and activity tracking.
                        </p>
                        <div className="mt-6 flex justify-center gap-3">
                            <Button asChild className="rounded-full">
                                <Link href="/account/profiles">Add Child Profile</Link>
                            </Button>
                            <Button asChild variant="outline" className="rounded-full">
                                <Link href="/free">Explore Free Resources</Link>
                            </Button>
                        </div>
                    </div>
                )}

                {/* Membership Callout if not member */}
                {profiles.length > 0 && !is_active_member && (
                    <div className="relative overflow-hidden rounded-3xl border border-amber-200/80 bg-linear-to-r from-amber-50/80 via-white to-sky-50/80 p-8 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                        <div className="max-w-2xl space-y-4">
                            <span className="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                <Sparkles className="size-3.5" /> Weekly Learning Programme
                            </span>
                            <h2 className="text-2xl font-black tracking-tight text-foreground">
                                Unlock Weekly Structured Learning for {active_profile?.nickname || 'Your Child'}
                            </h2>
                            <p className="text-sm leading-relaxed text-muted-foreground">
                                Our 90-Day Weekly Programme delivers curated, print-friendly packs across Maths, English, and EVS every week—with full answer keys and guided routines to build consistent study habits.
                            </p>
                            <div className="flex flex-wrap gap-3 pt-2">
                                <Button asChild className="rounded-full bg-[#2563EB] font-bold text-white shadow-md hover:bg-blue-700">
                                    <Link href="/membership">
                                        Explore Membership <ArrowRight className="ml-1.5 size-4" />
                                    </Link>
                                </Button>
                                <Button asChild variant="outline" className="rounded-full">
                                    <Link href="/membership/sample-month">View Sample Month</Link>
                                </Button>
                            </div>
                        </div>
                    </div>
                )}

                {/* Active Programme Card matching mockup screen 6 */}
                {profiles.length > 0 && (
                    <div className="space-y-6">
                        {/* Weekly Programme Card Header */}
                        <div className="rounded-3xl border border-slate-200/80 bg-card p-6 shadow-xs dark:border-slate-800">
                            <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div className="space-y-1">
                                    <div className="flex items-center gap-2">
                                        <span className="text-sm font-bold text-slate-900 dark:text-white">
                                            Weekly Programme
                                        </span>
                                        <span className="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                                            Week 3 of 12
                                        </span>
                                    </div>
                                    <p className="text-xs text-muted-foreground">
                                        Focus: Addition & Subtraction, Describing Words, Plants Around Us
                                    </p>
                                </div>

                                {/* Progress Bar matching mockup: 22% */}
                                <div className="min-w-[260px] space-y-1.5 rounded-2xl bg-slate-50 p-4 border border-slate-100 dark:border-slate-800 dark:bg-slate-900/50">
                                    <div className="flex items-center justify-between text-xs font-bold">
                                        <span className="text-slate-600 dark:text-slate-300">Routine Progress</span>
                                        <span className="text-[#2563EB]">
                                            {monthly_progress?.percentage ?? 22}%
                                        </span>
                                    </div>
                                    <div className="h-2.5 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                        <div
                                            className="h-full rounded-full bg-[#2563EB] transition-all duration-500"
                                            style={{ width: `${monthly_progress?.percentage ?? 22}%` }}
                                        />
                                    </div>
                                    <div className="flex justify-between text-[11px] text-muted-foreground">
                                        <span>2 of 9 completed</span>
                                        <Link href="/account/weekly-learning" className="text-primary hover:underline font-semibold">
                                            View All
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            {/* 3 Subject Cards matching mockup */}
                            <div className="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                                {/* Maths Card */}
                                <div className="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs dark:border-slate-800 dark:bg-slate-900">
                                    <div className="space-y-2">
                                        <div className="flex items-center justify-between">
                                            <span className="rounded-md bg-purple-50 px-2.5 py-1 text-xs font-extrabold text-purple-700 dark:bg-purple-950 dark:text-purple-300">
                                                Maths
                                            </span>
                                            <span className="rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-bold text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                                In Progress
                                            </span>
                                        </div>
                                        <h4 className="text-base font-bold text-foreground pt-1">
                                            Addition & Subtraction
                                        </h4>
                                        <p className="text-xs text-muted-foreground">
                                            2-digit regrouping practice with visual blocks.
                                        </p>
                                    </div>

                                    <div className="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                                        <Button asChild size="sm" className="w-full rounded-full bg-[#2563EB] text-xs font-bold text-white hover:bg-blue-700">
                                            <Link href="/free/class-2/maths/addition-practice-worksheet">
                                                Continue
                                            </Link>
                                        </Button>
                                    </div>
                                </div>

                                {/* English Card */}
                                <div className="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs dark:border-slate-800 dark:bg-slate-900">
                                    <div className="space-y-2">
                                        <div className="flex items-center justify-between">
                                            <span className="rounded-md bg-rose-50 px-2.5 py-1 text-xs font-extrabold text-rose-700 dark:bg-rose-950 dark:text-rose-300">
                                                English
                                            </span>
                                            <span className="rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800 dark:bg-rose-950 dark:text-rose-300">
                                                Not Started
                                            </span>
                                        </div>
                                        <h4 className="text-base font-bold text-foreground pt-1">
                                            Describing Words
                                        </h4>
                                        <p className="text-xs text-muted-foreground">
                                            Learning adjectives through animal and picture stories.
                                        </p>
                                    </div>

                                    <div className="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                                        <Button asChild size="sm" variant="outline" className="w-full rounded-full text-xs font-bold border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-300">
                                            <Link href="/account/weekly-learning">
                                                Start
                                            </Link>
                                        </Button>
                                    </div>
                                </div>

                                {/* EVS Card */}
                                <div className="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs dark:border-slate-800 dark:bg-slate-900">
                                    <div className="space-y-2">
                                        <div className="flex items-center justify-between">
                                            <span className="rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-extrabold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                                EVS
                                            </span>
                                            <span className="rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-bold text-rose-800 dark:bg-rose-950 dark:text-rose-300">
                                                Not Started
                                            </span>
                                        </div>
                                        <h4 className="text-base font-bold text-foreground pt-1">
                                            Plants Around Us
                                        </h4>
                                        <p className="text-xs text-muted-foreground">
                                            Parts of a plant and how leaves make food.
                                        </p>
                                    </div>

                                    <div className="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                                        <Button asChild size="sm" variant="outline" className="w-full rounded-full text-xs font-bold border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-300">
                                            <Link href="/account/weekly-learning">
                                                Start
                                            </Link>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Recent Downloads Section matching mockup screen 6 */}
                <div className="space-y-4">
                    <div className="flex items-center justify-between">
                        <h3 className="text-xl font-bold tracking-tight text-foreground">
                            Recent Downloads
                        </h3>
                        <Link href="/account/library" className="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                            View All <ArrowRight className="size-3" />
                        </Link>
                    </div>

                    <div className="space-y-3">
                        <div className="flex items-center justify-between rounded-2xl border border-slate-200/80 bg-card p-4 shadow-2xs transition-all hover:shadow-xs dark:border-slate-800">
                            <div className="flex items-center gap-3.5">
                                <div className="flex size-11 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950 dark:text-purple-400">
                                    <FileText className="size-5" />
                                </div>
                                <div>
                                    <h4 className="text-sm font-bold text-foreground">
                                        Addition Practice Worksheet
                                    </h4>
                                    <p className="text-xs text-muted-foreground">
                                        Class 2 · Maths
                                    </p>
                                </div>
                            </div>
                            <Button asChild size="sm" variant="outline" className="size-9 rounded-full p-0">
                                <a href="/free/class-2/maths/addition-practice-worksheet">
                                    <DownloadIcon className="size-4 text-slate-600" />
                                </a>
                            </Button>
                        </div>

                        <div className="flex items-center justify-between rounded-2xl border border-slate-200/80 bg-card p-4 shadow-2xs transition-all hover:shadow-xs dark:border-slate-800">
                            <div className="flex items-center gap-3.5">
                                <div className="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                                    <FileText className="size-5" />
                                </div>
                                <div>
                                    <h4 className="text-sm font-bold text-foreground">
                                        My Family - Worksheet
                                    </h4>
                                    <p className="text-xs text-muted-foreground">
                                        Class 1 · EVS · 2 days ago
                                    </p>
                                </div>
                            </div>
                            <Button asChild size="sm" variant="outline" className="size-9 rounded-full p-0">
                                <a href="/account/library">
                                    <DownloadIcon className="size-4 text-slate-600" />
                                </a>
                            </Button>
                        </div>

                        <div className="flex items-center justify-between rounded-2xl border border-slate-200/80 bg-card p-4 shadow-2xs transition-all hover:shadow-xs dark:border-slate-800">
                            <div className="flex items-center gap-3.5">
                                <div className="flex size-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-400">
                                    <FileText className="size-5" />
                                </div>
                                <div>
                                    <h4 className="text-sm font-bold text-foreground">
                                        Describing Words Worksheet
                                    </h4>
                                    <p className="text-xs text-muted-foreground">
                                        Class 2 · English · 3 days ago
                                    </p>
                                </div>
                            </div>
                            <Button asChild size="sm" variant="outline" className="size-9 rounded-full p-0">
                                <a href="/account/library">
                                    <DownloadIcon className="size-4 text-slate-600" />
                                </a>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
