import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    BarChart3,
    CheckCircle2,
    Download as DownloadIcon,
    Eye,
    FileCheck,
    Info,
    Sparkles,
    UserPlus,
} from 'lucide-react';

type Profile = {
    id: number;
    nickname: string;
    class_name: string | null;
    is_active: boolean;
};

type ProgressItem = {
    id: number;
    resource_id: number;
    title: string;
    slug: string;
    subject: string;
    type_label: string;
    status: 'not_started' | 'opened' | 'downloaded' | 'completed';
    status_label: string;
    opened_at: string | null;
    downloaded_at: string | null;
    completed_at: string | null;
    download_url: string | null;
};

type Stats = {
    completed: number;
    downloaded: number;
    opened: number;
};

type Props = {
    active_profile: { id: number; nickname: string; class_name: string | null } | null;
    profiles: Profile[];
    stats: Stats;
    progress: ProgressItem[];
};

export default function Progress({
    active_profile,
    profiles,
    stats,
    progress,
}: Props) {
    const [filter, setFilter] = useState<'all' | 'completed' | 'downloaded' | 'opened'>('all');

    const handleSwitchProfile = (profileId: number) => {
        router.post(`/account/profiles/${profileId}/switch`, {}, {
            preserveState: false,
            preserveScroll: true,
        });
    };

    const filteredProgress = progress.filter((item) => {
        if (filter === 'all') return true;
        return item.status === filter;
    });

    return (
        <AppLayout breadcrumbs={[{ title: 'Progress', href: '/account/progress' }]}>
            <Head title="Learning Progress — BrightLearners" />

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                {/* Header & Profile Switcher */}
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            {active_profile ? `${active_profile.nickname}'s Learning Progress` : 'Learning Progress'}
                        </h1>
                        <p className="text-sm text-muted-foreground mt-1">
                            Track activities completed, worksheets downloaded, and routine consistency.
                        </p>
                    </div>

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

                {/* Engagement Disclaimer Callout (M-08) */}
                <div className="flex items-start gap-3 rounded-xl border border-sky-200 bg-sky-50/50 p-4 text-xs text-sky-900 dark:border-sky-900/40 dark:bg-sky-950/20 dark:text-sky-300">
                    <Info className="h-4 w-4 shrink-0 text-sky-600 dark:text-sky-400 mt-0.5" />
                    <p className="leading-relaxed">
                        <strong>Engagement Signal:</strong> This log records activities opened and completed to celebrate steady daily habits. Skill mastery and assessment outcomes remain separate and are evaluated in dedicated review packs.
                    </p>
                </div>

                {/* Stats Cards Grid */}
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div className="flex items-center gap-4 rounded-xl border bg-card p-5 shadow-sm">
                        <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                            <CheckCircle2 className="h-6 w-6" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold tracking-tight text-foreground">{stats.completed}</div>
                            <div className="text-xs text-muted-foreground font-medium">Activities Completed</div>
                        </div>
                    </div>

                    <div className="flex items-center gap-4 rounded-xl border bg-card p-5 shadow-sm">
                        <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                            <DownloadIcon className="h-6 w-6" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold tracking-tight text-foreground">{stats.downloaded}</div>
                            <div className="text-xs text-muted-foreground font-medium">Worksheets Downloaded</div>
                        </div>
                    </div>

                    <div className="flex items-center gap-4 rounded-xl border bg-card p-5 shadow-sm">
                        <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                            <Eye className="h-6 w-6" />
                        </div>
                        <div>
                            <div className="text-2xl font-bold tracking-tight text-foreground">{stats.opened}</div>
                            <div className="text-xs text-muted-foreground font-medium">Materials Opened</div>
                        </div>
                    </div>
                </div>

                {/* Activity List & Filters */}
                <div className="space-y-4">
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-border pb-3">
                        <h2 className="text-base font-semibold tracking-tight">Activity Log</h2>

                        <div className="flex items-center gap-2">
                            {(['all', 'completed', 'downloaded', 'opened'] as const).map((tab) => (
                                <button
                                    key={tab}
                                    type="button"
                                    onClick={() => setFilter(tab)}
                                    className={`rounded-lg px-2.5 py-1 text-xs font-medium capitalize transition-colors ${
                                        filter === tab
                                            ? 'bg-secondary text-secondary-foreground font-semibold'
                                            : 'text-muted-foreground hover:text-foreground'
                                    }`}
                                >
                                    {tab}
                                </button>
                            ))}
                        </div>
                    </div>

                    {filteredProgress.length === 0 ? (
                        <div className="rounded-xl border border-dashed p-10 text-center space-y-3">
                            <div className="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-muted text-muted-foreground">
                                <FileCheck className="h-5 w-5" />
                            </div>
                            <div className="text-sm font-semibold">No Activity Recorded Yet</div>
                            <p className="mx-auto max-w-sm text-xs text-muted-foreground">
                                When your child starts opening worksheets and completing activities on the parent dashboard, their progress will appear here.
                            </p>
                            <Button asChild size="sm" className="mt-2">
                                <Link href="/account/dashboard">Go to Dashboard</Link>
                            </Button>
                        </div>
                    ) : (
                        <div className="divide-y rounded-xl border bg-card">
                            {filteredProgress.map((item) => (
                                <div key={item.id} className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4">
                                    <div className="space-y-1">
                                        <div className="flex items-center gap-2">
                                            <span className="rounded bg-muted px-2 py-0.5 text-[11px] font-medium text-foreground">
                                                {item.subject}
                                            </span>
                                            <span className={`inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium capitalize ${
                                                item.status === 'completed'
                                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300'
                                                    : item.status === 'downloaded'
                                                    ? 'bg-blue-100 text-blue-800 dark:bg-blue-950/50 dark:text-blue-300'
                                                    : 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300'
                                            }`}>
                                                {item.status_label}
                                            </span>
                                        </div>

                                        <h3 className="text-sm font-bold text-foreground">
                                            <a href={`/account/library/${item.slug}`} className="hover:underline">
                                                {item.title}
                                            </a>
                                        </h3>

                                        <div className="text-[11px] text-muted-foreground">
                                            {item.completed_at && `Completed on ${item.completed_at}`}
                                            {!item.completed_at && item.downloaded_at && `Downloaded on ${item.downloaded_at}`}
                                            {!item.completed_at && !item.downloaded_at && item.opened_at && `Opened on ${item.opened_at}`}
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-2 shrink-0">
                                        <Button asChild size="sm" variant="outline" className="h-8 text-xs">
                                            <Link href={`/account/library/${item.slug}`}>
                                                Details
                                            </Link>
                                        </Button>
                                        {item.download_url && (
                                            <Button asChild size="sm" className="h-8 text-xs">
                                                <a href={item.download_url} target="_blank" rel="noopener noreferrer">
                                                    <DownloadIcon className="mr-1.5 h-3.5 w-3.5" /> Download
                                                </a>
                                            </Button>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
