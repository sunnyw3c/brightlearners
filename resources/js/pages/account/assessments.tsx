import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    Award,
    CheckCircle2,
    Clock,
    ExternalLink,
    HelpCircle,
    PlusCircle,
    Sparkles,
} from 'lucide-react';

type Profile = {
    id: number;
    nickname: string;
    class_name: string | null;
    is_active: boolean;
};

type SkillBandItem = {
    skill_name: string;
    band: string;
    band_label: string;
    color_class: string;
};

type AttemptItem = {
    id: number;
    assessment_title: string;
    class_name: string;
    subject_name: string;
    child_nickname: string;
    status: string;
    parent_feedback: string | null;
    completed_at: string | null;
    skill_bands: SkillBandItem[];
};

type Props = {
    profiles: Profile[];
    selected_profile_id: number | null;
    attempts: AttemptItem[];
};

export default function AccountAssessments({ profiles, selected_profile_id, attempts }: Props) {
    const activeProfile = profiles.find((p) => p.is_active) ?? profiles[0] ?? null;

    const handleSwitchProfile = (profileId: number) => {
        router.get(`/account/assessments`, { profile_id: profileId }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Skill Checks', href: '/account/assessments' }]}>
            <Head title="Skill Checks & Assessments — BrightLearners" />

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                {/* Header & Profiles */}
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            {activeProfile ? `${activeProfile.nickname}'s Skill Checks` : 'Skill Checks & Assessments'}
                        </h1>
                        <p className="text-sm text-muted-foreground mt-1">
                            Informal diagnostic checks to identify learning strengths and tailored next steps.
                        </p>
                    </div>

                    <div className="flex flex-wrap items-center gap-2">
                        {profiles.map((p) => {
                            const isSelected = p.id === (activeProfile?.id ?? selected_profile_id);
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
                                        <span className="opacity-75">({p.class_name})</span>
                                    )}
                                </button>
                            );
                        })}
                    </div>
                </div>

                {/* Available Starter Checks Banner */}
                <div className="rounded-3xl border bg-gradient-to-r from-indigo-50/50 via-purple-50/30 to-pink-50/40 p-6 dark:from-indigo-950/20 dark:via-purple-950/10 dark:to-pink-950/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div className="space-y-1">
                        <div className="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-primary">
                            <Sparkles className="h-3.5 w-3.5" />
                            Diagnostic Learning Checks
                        </div>
                        <h3 className="text-lg font-bold">Start a New 10-Minute Check</h3>
                        <p className="text-xs sm:text-sm text-muted-foreground max-w-xl">
                            Short, calm activities covering foundational topics. Generates immediate practice worksheets for weak skills.
                        </p>
                    </div>

                    <div className="flex flex-wrap gap-2">
                        <Link href="/assessment/class-1-maths-starter-check">
                            <Button size="sm" variant="outline" className="text-xs">
                                Class 1 Maths
                            </Button>
                        </Link>
                        <Link href="/assessment/class-2-maths-starter-check">
                            <Button size="sm" variant="outline" className="text-xs">
                                Class 2 Maths
                            </Button>
                        </Link>
                        <Link href="/assessment/class-3-maths-starter-check">
                            <Button size="sm" variant="outline" className="text-xs">
                                Class 3 Maths
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Attempts List */}
                <div className="space-y-4">
                    <h2 className="text-xl font-bold">Completed Checks</h2>

                    {attempts.length === 0 ? (
                        <div className="rounded-2xl border border-dashed p-10 text-center space-y-3">
                            <Award className="mx-auto h-10 w-10 text-muted-foreground/60" />
                            <h3 className="font-semibold text-base">No completed checks yet</h3>
                            <p className="text-sm text-muted-foreground max-w-md mx-auto">
                                Start an informal check above to discover learning confidence and unlock personalized recommendations.
                            </p>
                        </div>
                    ) : (
                        <div className="grid gap-4">
                            {attempts.map((attempt) => (
                                <div
                                    key={attempt.id}
                                    className="rounded-2xl border bg-card p-6 shadow-sm hover:shadow transition-all space-y-4"
                                >
                                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b pb-3">
                                        <div>
                                            <div className="flex items-center gap-2">
                                                <h3 className="font-bold text-lg text-foreground">
                                                    {attempt.assessment_title}
                                                </h3>
                                                <span className="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                                    {attempt.class_name} • {attempt.subject_name}
                                                </span>
                                            </div>
                                            <div className="flex items-center gap-3 text-xs text-muted-foreground mt-1">
                                                <span className="flex items-center gap-1">
                                                    <Clock className="h-3.5 w-3.5" />
                                                    {attempt.completed_at || 'In progress'}
                                                </span>
                                                {attempt.parent_feedback && (
                                                    <span className="rounded bg-muted px-2 py-0.5 font-medium">
                                                        Feedback: {attempt.parent_feedback}
                                                    </span>
                                                )}
                                            </div>
                                        </div>

                                        <Link
                                            href={`/assessment/attempt/${attempt.id}/result`}
                                            className="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline self-start sm:self-center"
                                        >
                                            View Full Result & Recommendations
                                            <ExternalLink className="h-3.5 w-3.5" />
                                        </Link>
                                    </div>

                                    {/* Skill Bands Grid */}
                                    <div className="flex flex-wrap gap-2.5">
                                        {attempt.skill_bands.map((sb, idx) => {
                                            const isConfident = sb.band === 'confident';
                                            const isComfortable = sb.band === 'comfortable';

                                            return (
                                                <div
                                                    key={idx}
                                                    className={`rounded-xl border px-3 py-1.5 text-xs flex items-center gap-2 ${
                                                        isConfident
                                                            ? 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-300'
                                                            : isComfortable
                                                            ? 'border-blue-200 bg-blue-50 text-blue-900 dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-300'
                                                            : 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-300'
                                                    }`}
                                                >
                                                    <span className="font-semibold">{sb.skill_name}:</span>
                                                    <span className="font-bold uppercase tracking-wider text-[10px]">
                                                        {sb.band_label}
                                                    </span>
                                                </div>
                                            );
                                        })}
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
