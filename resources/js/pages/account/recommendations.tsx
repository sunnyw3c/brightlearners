import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    ArrowRight,
    BookOpen,
    CheckCircle2,
    ExternalLink,
    HelpCircle,
    Lightbulb,
    Sparkles,
} from 'lucide-react';

type Profile = {
    id: number;
    nickname: string;
    class_name: string | null;
    is_active: boolean;
};

type RecommendationItem = {
    id: number;
    skill_name: string;
    reason_code: string;
    reason_sentence: string;
    target_type: string;
    target_id: number;
    target_title: string;
    target_summary: string;
    target_url: string;
    clicked_at: string | null;
    acted_at: string | null;
};

type Props = {
    profiles: Profile[];
    selected_profile_id: number | null;
    recommendations: RecommendationItem[];
};

export default function AccountRecommendations({
    profiles,
    selected_profile_id,
    recommendations,
}: Props) {
    const activeProfile =
        profiles.find((p) => p.is_active) ?? profiles[0] ?? null;
    const [actedMap, setActedMap] = useState<Record<number, boolean>>({});

    const handleSwitchProfile = (profileId: number) => {
        router.get(
            `/account/recommendations`,
            { profile_id: profileId },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    const handleTrackClick = (recId: number) => {
        fetch(`/account/recommendations/${recId}/click`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    (
                        document.querySelector(
                            'meta[name="csrf-token"]',
                        ) as HTMLMetaElement
                    )?.content || '',
            },
        });
    };

    const handleMarkActed = (recId: number) => {
        setActedMap((prev) => ({ ...prev, [recId]: true }));
        fetch(`/account/recommendations/${recId}/act`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    (
                        document.querySelector(
                            'meta[name="csrf-token"]',
                        ) as HTMLMetaElement
                    )?.content || '',
            },
        });
    };

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Recommendations', href: '/account/recommendations' },
            ]}
        >
            <Head title="Learning Recommendations" />

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                {/* Header & Profiles */}
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            {activeProfile
                                ? `${activeProfile.nickname}'s Learning Recommendations`
                                : 'Learning Recommendations'}
                        </h1>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Personalized worksheets and activities selected
                            based on recent skill checks.
                        </p>
                    </div>

                    <div className="flex flex-wrap items-center gap-2">
                        {profiles.map((p) => {
                            const isSelected =
                                p.id ===
                                (activeProfile?.id ?? selected_profile_id);
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
                                    {p.nickname}
                                    {p.class_name && (
                                        <span className="opacity-75">
                                            ({p.class_name})
                                        </span>
                                    )}
                                </button>
                            );
                        })}
                    </div>
                </div>

                {/* Explanatory Banner */}
                <div className="flex items-start gap-4 rounded-3xl border bg-gradient-to-r from-sky-50/60 via-indigo-50/40 to-emerald-50/40 p-6 dark:from-sky-950/20 dark:via-indigo-950/10 dark:to-emerald-950/10">
                    <Lightbulb className="mt-0.5 h-6 w-6 shrink-0 text-amber-500" />
                    <div className="space-y-1 text-xs leading-relaxed sm:text-sm">
                        <span className="font-semibold text-foreground">
                            Explainable & Deterministic Recommendations:
                        </span>
                        <p className="text-muted-foreground">
                            Every recommendation stores a transparent reason
                            explaining why it was chosen for{' '}
                            {activeProfile?.nickname ?? 'your child'}. We
                            prioritize reinforcing topics that need extra
                            practice before introducing extension challenges.
                        </p>
                    </div>
                </div>

                {/* Recommendations Grid */}
                {recommendations.length === 0 ? (
                    <div className="space-y-3 rounded-2xl border border-dashed p-12 text-center">
                        <Sparkles className="mx-auto h-10 w-10 text-muted-foreground/60" />
                        <h3 className="text-base font-semibold">
                            No active recommendations
                        </h3>
                        <p className="mx-auto max-w-md text-sm text-muted-foreground">
                            Take a short 10-minute skill check to unlock
                            tailored learning activities for{' '}
                            {activeProfile?.nickname ?? 'your child'}.
                        </p>
                        <div className="pt-2">
                            <Link href="/account/assessments">
                                <Button className="gap-2">
                                    Take a Skill Check
                                    <ArrowRight className="h-4 w-4" />
                                </Button>
                            </Link>
                        </div>
                    </div>
                ) : (
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {recommendations.map((rec) => {
                            const isDone =
                                actedMap[rec.id] || Boolean(rec.acted_at);

                            return (
                                <div
                                    key={rec.id}
                                    className="flex flex-col justify-between space-y-5 rounded-3xl border bg-card p-6 shadow-sm transition-all hover:shadow-md"
                                >
                                    <div className="space-y-3">
                                        <div className="inline-flex items-center gap-1.5 rounded-xl bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary">
                                            <Sparkles className="h-3 w-3" />
                                            {rec.reason_sentence}
                                        </div>

                                        <h3 className="text-lg leading-snug font-bold text-foreground">
                                            {rec.target_title}
                                        </h3>

                                        <p className="line-clamp-2 text-xs text-muted-foreground">
                                            {rec.target_summary}
                                        </p>

                                        <div className="pt-1">
                                            <span className="inline-block rounded-full bg-muted px-2.5 py-0.5 text-[11px] font-medium text-muted-foreground">
                                                Skill: {rec.skill_name}
                                            </span>
                                        </div>
                                    </div>

                                    <div className="space-y-2 border-t pt-4">
                                        <a
                                            href={rec.target_url}
                                            onClick={() =>
                                                handleTrackClick(rec.id)
                                            }
                                            className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-primary-foreground shadow-sm transition-colors hover:bg-primary/90"
                                        >
                                            <span>Open Resource</span>
                                            <ExternalLink className="h-3.5 w-3.5" />
                                        </a>

                                        <button
                                            type="button"
                                            onClick={() =>
                                                handleMarkActed(rec.id)
                                            }
                                            disabled={isDone}
                                            className={`inline-flex w-full items-center justify-center gap-1.5 rounded-xl border px-3 py-2 text-xs font-semibold transition-all ${
                                                isDone
                                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300'
                                                    : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                                            }`}
                                        >
                                            <CheckCircle2 className="h-3.5 w-3.5" />
                                            {isDone
                                                ? 'Completed with Child'
                                                : 'Mark as Practiced'}
                                        </button>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
