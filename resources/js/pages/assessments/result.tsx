import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { Button } from '@/components/ui/button';
import {
    ArrowRight,
    Award,
    BookOpen,
    CheckCircle2,
    ExternalLink,
    Heart,
    Info,
    Smile,
    Sparkles,
} from 'lucide-react';

type SkillScoreItem = {
    id: number;
    skill_name: string;
    band: string;
    band_label: string;
    band_description: string;
    color_class: string;
};

type RecommendationItem = {
    id: number;
    skill_name: string;
    reason_code: string;
    reason_sentence: string;
    target_type: string;
    target_id: number;
    target_title: string;
    target_url: string;
};

type AttemptResult = {
    id: number;
    assessment_title: string;
    class_name: string;
    subject_name: string;
    child_nickname: string;
    completed_at: string;
    parent_feedback: string | null;
    skill_scores: SkillScoreItem[];
    answers_count: number;
    correct_count: number;
};

type Props = {
    attempt: AttemptResult;
    recommendations: RecommendationItem[];
};

export default function AssessmentResult({ attempt, recommendations }: Props) {
    const [feedback, setFeedback] = useState<string | null>(attempt.parent_feedback);
    const [savingFeedback, setSavingFeedback] = useState(false);

    const handleFeedbackSelect = (val: 'easy' | 'comfortable' | 'needs_practice') => {
        setFeedback(val);
        setSavingFeedback(true);
        router.post(
            `/account/assessments/${attempt.id}/feedback`,
            { feedback: val },
            {
                preserveScroll: true,
                onFinish: () => setSavingFeedback(false),
            }
        );
    };

    const handleRecommendationClick = (recId: number) => {
        // Send signal to record click
        fetch(`/account/recommendations/${recId}/click`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
        });
    };

    return (
        <PublicLayout>
            <Head title={`Results: ${attempt.assessment_title} — BrightLearners`} />

            <div className="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8 space-y-10">
                {/* Result Hero */}
                <div className="rounded-3xl bg-gradient-to-br from-emerald-900 via-teal-800 to-sky-900 p-8 text-white shadow-xl space-y-4">
                    <div className="inline-flex items-center gap-2 rounded-full bg-white/20 px-3.5 py-1 text-xs font-semibold backdrop-blur-md">
                        <Award className="h-4 w-4 text-amber-300" />
                        Learning Check Completed • {attempt.completed_at}
                    </div>

                    <h1 className="text-3xl font-extrabold tracking-tight sm:text-4xl">
                        {attempt.child_nickname}&apos;s Skill Insights
                    </h1>

                    <p className="max-w-2xl text-sm sm:text-base text-white/90">
                        {attempt.assessment_title} ({attempt.class_name} • {attempt.subject_name})
                    </p>
                </div>

                {/* Friendly Educational Guidance Banner */}
                <div className="flex items-start gap-3.5 rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 text-emerald-950 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-200">
                    <Info className="h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" />
                    <div className="text-xs sm:text-sm leading-relaxed">
                        <span className="font-semibold">Gentle Informal Check:</span> Results are presented as simple skill bands to highlight where your child feels confident and which topics benefit from supportive practice.
                    </div>
                </div>

                {/* Skill Bands Card */}
                <div className="rounded-3xl border bg-card p-6 sm:p-8 shadow-sm space-y-6">
                    <div className="flex items-center justify-between border-b pb-4">
                        <div>
                            <h2 className="text-xl font-bold">Skill Mastery Overview</h2>
                            <p className="text-xs sm:text-sm text-muted-foreground mt-0.5">
                                Performance grouped into clear learning bands.
                            </p>
                        </div>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-2">
                        {attempt.skill_scores.map((skill) => {
                            const isConfident = skill.band === 'confident';
                            const isComfortable = skill.band === 'comfortable';
                            const isNeedsPractice = skill.band === 'needs_practice';

                            return (
                                <div
                                    key={skill.id}
                                    className={`rounded-2xl border p-5 transition-all space-y-3 ${
                                        isConfident
                                            ? 'border-emerald-200 bg-emerald-50/40 dark:border-emerald-900/40 dark:bg-emerald-950/20'
                                            : isComfortable
                                            ? 'border-blue-200 bg-blue-50/40 dark:border-blue-900/40 dark:bg-blue-950/20'
                                            : 'border-amber-200 bg-amber-50/40 dark:border-amber-900/40 dark:bg-amber-950/20'
                                    }`}
                                >
                                    <div className="flex items-start justify-between gap-2">
                                        <h3 className="font-semibold text-base text-foreground">
                                            {skill.skill_name}
                                        </h3>
                                        <span
                                            className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider ${
                                                isConfident
                                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300'
                                                    : isComfortable
                                                    ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300'
                                                    : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300'
                                            }`}
                                        >
                                            {skill.band_label}
                                        </span>
                                    </div>

                                    <p className="text-xs sm:text-sm text-muted-foreground leading-relaxed">
                                        {skill.band_description}
                                    </p>
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* Recommendations Section */}
                {recommendations.length > 0 && (
                    <div className="rounded-3xl border bg-card p-6 sm:p-8 shadow-sm space-y-6">
                        <div className="flex items-center justify-between border-b pb-4">
                            <div>
                                <div className="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-primary">
                                    <Sparkles className="h-3.5 w-3.5" />
                                    Tailored Next Steps
                                </div>
                                <h2 className="text-xl font-bold mt-1">Recommended Practice for {attempt.child_nickname}</h2>
                            </div>
                            <Link
                                href="/account/recommendations"
                                className="text-xs font-semibold text-primary hover:underline inline-flex items-center gap-1"
                            >
                                View All
                                <ArrowRight className="h-3.5 w-3.5" />
                            </Link>
                        </div>

                        <div className="grid gap-4 sm:grid-cols-2">
                            {recommendations.map((rec) => (
                                <div
                                    key={rec.id}
                                    className="flex flex-col justify-between rounded-2xl border p-5 hover:border-primary/50 transition-all space-y-4 bg-muted/20"
                                >
                                    <div className="space-y-2">
                                        <div className="rounded-md bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary w-fit">
                                            {rec.reason_sentence}
                                        </div>
                                        <h3 className="font-semibold text-base text-foreground leading-snug">
                                            {rec.target_title}
                                        </h3>
                                        <p className="text-xs text-muted-foreground">
                                            Focus skill: {rec.skill_name}
                                        </p>
                                    </div>

                                    <a
                                        href={rec.target_url}
                                        onClick={() => handleRecommendationClick(rec.id)}
                                        className="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground hover:bg-primary/90 transition-colors"
                                    >
                                        <span>Open Activity</span>
                                        <ExternalLink className="h-3.5 w-3.5" />
                                    </a>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* Parent Feedback Selection */}
                <div className="rounded-3xl border bg-card p-6 sm:p-8 shadow-sm space-y-4">
                    <h3 className="text-lg font-bold">How did this check feel for {attempt.child_nickname}?</h3>
                    <p className="text-xs sm:text-sm text-muted-foreground">
                        Your observation helps refine pacing and worksheet recommendations.
                    </p>

                    <div className="flex flex-wrap gap-3 pt-1">
                        {(['easy', 'comfortable', 'needs_practice'] as const).map((level) => {
                            const isSelected = feedback === level;
                            const label =
                                level === 'easy'
                                    ? 'Easy (Too light)'
                                    : level === 'comfortable'
                                    ? 'Comfortable (Just right)'
                                    : 'Needs Practice (Challenging)';

                            return (
                                <button
                                    key={level}
                                    type="button"
                                    onClick={() => handleFeedbackSelect(level)}
                                    className={`rounded-full px-4 py-2 text-xs font-semibold transition-all ${
                                        isSelected
                                            ? 'bg-primary text-primary-foreground shadow-sm ring-2 ring-primary ring-offset-1'
                                            : 'bg-muted text-muted-foreground hover:bg-muted/80 hover:text-foreground'
                                    }`}
                                >
                                    {label}
                                </button>
                            );
                        })}
                    </div>
                </div>

                {/* Footer Navigation */}
                <div className="flex flex-wrap items-center justify-between gap-4 pt-4">
                    <Link
                        href="/account/assessments"
                        className="text-sm font-semibold text-muted-foreground hover:text-foreground"
                    >
                        ← Back to Assessment History
                    </Link>

                    <Link href="/account/dashboard">
                        <Button className="gap-2">
                            Go to Dashboard
                            <ArrowRight className="h-4 w-4" />
                        </Button>
                    </Link>
                </div>
            </div>
        </PublicLayout>
    );
}
