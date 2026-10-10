import { useState } from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    CheckCircle2,
    Clock,
    Download as DownloadIcon,
    Lock,
    Sparkles,
    User as UserIcon,
    AlertCircle,
    BookOpen,
} from 'lucide-react';

type LearningItem = {
    id: number;
    resource_id: number;
    title: string;
    slug: string;
    type: string;
    type_label: string;
    guidance: string | null;
    required: boolean;
    current_version: string | null;
    download_url: string | null;
};

type WeekData = {
    id: number;
    week_number: number;
    title: string;
    emphasis: string | null;
    is_released: boolean;
    release_at: string;
    released_at: string | null;
    items: LearningItem[];
};

type ProgramData = {
    id: number;
    title: string;
    month: number;
    year: number;
    weeks: WeekData[];
};

type ProfileProgram = {
    profile_id: number;
    profile_name: string;
    class_name: string | null;
    program: ProgramData | null;
};

type Props = {
    is_active_member: boolean;
    programmes: ProfileProgram[];
};

export default function WeeklyLearningIndex({
    is_active_member,
    programmes,
}: Props) {
    const [selectedProfileId, setSelectedProfileId] = useState<number | null>(
        programmes.length > 0 ? programmes[0].profile_id : null,
    );

    const activeProfile =
        programmes.find((p) => p.profile_id === selectedProfileId) ??
        programmes[0];

    return (
        <AppLayout>
            <Head title="Weekly Learning Programme — BrightLearners" />

            <div className="mx-auto max-w-5xl space-y-8 px-4 py-8">
                <header className="space-y-1">
                    <div className="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-0.5 text-xs font-semibold text-primary">
                        <Sparkles className="h-3.5 w-3.5" />
                        <span>Curriculum Programme</span>
                    </div>
                    <h1 className="text-3xl font-bold tracking-tight">
                        Weekly Learning
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Structured weekly worksheet releases and parent guidance
                        for your children.
                    </p>
                </header>

                {!is_active_member && (
                    <div className="flex flex-col justify-between gap-4 rounded-3xl border border-amber-200 bg-amber-50/50 p-6 sm:flex-row sm:items-center dark:border-amber-900/50 dark:bg-amber-950/20">
                        <div className="flex items-start gap-3">
                            <AlertCircle className="mt-0.5 h-5 w-5 shrink-0 text-amber-600" />
                            <div className="space-y-1">
                                <h2 className="text-sm font-bold text-amber-900 dark:text-amber-200">
                                    Active Membership Required
                                </h2>
                                <p className="text-xs text-amber-700 dark:text-amber-300">
                                    You need an active 90-day family pilot
                                    membership to download released weekly
                                    curriculum packs.
                                </p>
                            </div>
                        </div>
                        <Button asChild size="sm" className="shrink-0">
                            <a href="/membership">Join 90-Day Pilot</a>
                        </Button>
                    </div>
                )}

                {programmes.length === 0 ? (
                    <div className="space-y-4 rounded-3xl border bg-card p-12 text-center">
                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
                            <UserIcon className="h-6 w-6" />
                        </div>
                        <div className="space-y-1">
                            <h2 className="text-lg font-bold">
                                No Child Profiles Created
                            </h2>
                            <p className="mx-auto max-w-md text-xs text-muted-foreground">
                                To see weekly curriculum programmes tailored to
                                your child, please create a learning profile and
                                assign their class.
                            </p>
                        </div>
                        <Button asChild size="sm">
                            <a href="/account/profiles">
                                Manage Learning Profiles
                            </a>
                        </Button>
                    </div>
                ) : (
                    <div className="space-y-6">
                        {/* Profile Switcher Tabs */}
                        {programmes.length > 1 && (
                            <div className="flex gap-2 overflow-x-auto border-b pb-3">
                                {programmes.map((p) => (
                                    <button
                                        key={p.profile_id}
                                        onClick={() =>
                                            setSelectedProfileId(p.profile_id)
                                        }
                                        className={`rounded-xl px-4 py-2 text-xs font-semibold transition-all ${
                                            p.profile_id === selectedProfileId
                                                ? 'bg-primary text-primary-foreground shadow-sm'
                                                : 'bg-muted/50 text-muted-foreground hover:bg-muted hover:text-foreground'
                                        }`}
                                    >
                                        {p.profile_name} (
                                        {p.class_name ?? 'No Class'})
                                    </button>
                                ))}
                            </div>
                        )}

                        {activeProfile && (
                            <div className="space-y-6">
                                <div className="flex items-center justify-between border-b pb-4">
                                    <div>
                                        <h2 className="flex items-center gap-2 text-xl font-bold">
                                            <span>
                                                {activeProfile.profile_name}
                                            </span>
                                            {activeProfile.class_name && (
                                                <span className="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                                                    {activeProfile.class_name}
                                                </span>
                                            )}
                                        </h2>
                                        {activeProfile.program && (
                                            <p className="text-xs text-muted-foreground">
                                                {activeProfile.program.title}
                                            </p>
                                        )}
                                    </div>
                                </div>

                                {!activeProfile.program ? (
                                    <div className="rounded-2xl border bg-card p-8 text-center text-xs text-muted-foreground">
                                        No active curriculum programme is
                                        currently published for this class.
                                    </div>
                                ) : (
                                    <div className="space-y-6">
                                        {activeProfile.program.weeks.map(
                                            (week) => (
                                                <div
                                                    key={week.id}
                                                    className={`space-y-5 rounded-3xl border bg-card p-6 shadow-sm transition-all ${
                                                        !week.is_released
                                                            ? 'bg-muted/20 opacity-75'
                                                            : ''
                                                    }`}
                                                >
                                                    <div className="flex flex-col justify-between gap-2 border-b pb-4 sm:flex-row sm:items-center">
                                                        <div className="space-y-1">
                                                            <div className="flex items-center gap-2">
                                                                <span
                                                                    className={`inline-flex rounded px-2 py-0.5 text-xs font-bold ${
                                                                        week.is_released
                                                                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300'
                                                                            : 'bg-muted text-muted-foreground'
                                                                    }`}
                                                                >
                                                                    Week{' '}
                                                                    {
                                                                        week.week_number
                                                                    }
                                                                </span>
                                                                <h3 className="text-base font-bold">
                                                                    {week.title}
                                                                </h3>
                                                            </div>
                                                            {week.emphasis && (
                                                                <p className="text-xs text-muted-foreground">
                                                                    {
                                                                        week.emphasis
                                                                    }
                                                                </p>
                                                            )}
                                                        </div>

                                                        <div className="flex items-center gap-1.5 text-xs font-medium">
                                                            {week.is_released ? (
                                                                <span className="flex items-center gap-1 text-emerald-600">
                                                                    <CheckCircle2 className="h-4 w-4" />{' '}
                                                                    Released{' '}
                                                                    {
                                                                        week.released_at
                                                                    }
                                                                </span>
                                                            ) : (
                                                                <span className="flex items-center gap-1 text-muted-foreground">
                                                                    <Lock className="h-3.5 w-3.5" />{' '}
                                                                    Releases on{' '}
                                                                    {
                                                                        week.release_at
                                                                    }
                                                                </span>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Week Items */}
                                                    {!week.is_released ? (
                                                        <div className="space-y-1 rounded-2xl border border-dashed p-6 text-center text-xs text-muted-foreground">
                                                            <Clock className="mx-auto mb-2 h-5 w-5 text-muted-foreground" />
                                                            <p className="font-medium text-foreground">
                                                                Content Locked
                                                                Until Release
                                                            </p>
                                                            <p>
                                                                This week’s
                                                                materials will
                                                                automatically
                                                                unlock on{' '}
                                                                {
                                                                    week.release_at
                                                                }
                                                                .
                                                            </p>
                                                        </div>
                                                    ) : week.items.length ===
                                                      0 ? (
                                                        <div className="text-xs text-muted-foreground italic">
                                                            No learning
                                                            materials in this
                                                            week yet.
                                                        </div>
                                                    ) : (
                                                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                                            {week.items.map(
                                                                (item) => (
                                                                    <div
                                                                        key={
                                                                            item.id
                                                                        }
                                                                        className="flex flex-col justify-between space-y-3 rounded-2xl border bg-card p-4 transition-colors hover:border-primary/50"
                                                                    >
                                                                        <div className="space-y-2">
                                                                            <div className="flex items-center justify-between text-xs">
                                                                                <span className="rounded bg-primary/10 px-2 py-0.5 font-medium text-primary capitalize">
                                                                                    {
                                                                                        item.type_label
                                                                                    }
                                                                                </span>
                                                                                {item.current_version && (
                                                                                    <span className="font-mono text-[11px] text-muted-foreground">
                                                                                        v
                                                                                        {
                                                                                            item.current_version
                                                                                        }
                                                                                    </span>
                                                                                )}
                                                                            </div>
                                                                            <h4 className="line-clamp-2 text-sm leading-snug font-bold">
                                                                                {
                                                                                    item.title
                                                                                }
                                                                            </h4>
                                                                            {item.guidance && (
                                                                                <div className="space-y-1 rounded-xl border bg-muted/40 p-2.5 text-xs text-muted-foreground">
                                                                                    <div className="flex items-center gap-1 text-[11px] font-semibold text-foreground">
                                                                                        <BookOpen className="h-3 w-3 text-primary" />{' '}
                                                                                        Parent
                                                                                        Notes:
                                                                                    </div>
                                                                                    <p className="line-clamp-3 leading-relaxed">
                                                                                        {
                                                                                            item.guidance
                                                                                        }
                                                                                    </p>
                                                                                </div>
                                                                            )}
                                                                        </div>

                                                                        {item.download_url &&
                                                                            is_active_member && (
                                                                                <div className="border-t pt-2">
                                                                                    <Button
                                                                                        asChild
                                                                                        size="sm"
                                                                                        className="w-full text-xs"
                                                                                    >
                                                                                        <a
                                                                                            href={
                                                                                                item.download_url
                                                                                            }
                                                                                            target="_blank"
                                                                                            rel="noopener noreferrer"
                                                                                        >
                                                                                            <DownloadIcon className="mr-1.5 h-3.5 w-3.5" />{' '}
                                                                                            Download
                                                                                            Pack
                                                                                        </a>
                                                                                    </Button>
                                                                                </div>
                                                                            )}
                                                                    </div>
                                                                ),
                                                            )}
                                                        </div>
                                                    )}
                                                </div>
                                            ),
                                        )}
                                    </div>
                                )}
                            </div>
                        )}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
