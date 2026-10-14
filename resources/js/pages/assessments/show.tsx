import { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { Button } from '@/components/ui/button';
import {
    ArrowRight,
    Award,
    CheckCircle2,
    HelpCircle,
    Info,
    Sparkles,
} from 'lucide-react';

type Question = {
    id: number;
    prompt: string;
    type: string;
    difficulty: string;
    points: number;
    options: string[];
};

type Section = {
    id: number;
    title: string;
    sort_order: number;
    questions: Question[];
};

type AssessmentData = {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    version: number;
    class_name: string;
    subject_name: string;
    sections: Section[];
};

type Profile = {
    id: number;
    nickname: string;
    class_id: number | null;
};

type Props = {
    assessment: AssessmentData;
    profiles: Profile[];
};

export default function AssessmentShow({ assessment, profiles }: Props) {
    const [selectedProfileId, setSelectedProfileId] = useState<number | null>(
        profiles.length > 0 ? profiles[0].id : null,
    );
    const [attemptId, setAttemptId] = useState<number | null>(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const attempt = urlParams.get('attempt');
        return attempt ? parseInt(attempt, 10) : null;
    });

    const [answers, setAnswers] = useState<Record<number, string>>({});
    const [isSubmitting, setIsSubmitting] = useState(false);

    const allQuestions = assessment.sections.flatMap((s) => s.questions);
    const answeredCount = Object.keys(answers).length;

    const handleStartAttempt = () => {
        if (!selectedProfileId) return;

        router.post(
            `/assessment/${assessment.slug}/attempt`,
            { profile_id: selectedProfileId },
            {
                onSuccess: (page) => {
                    // Url may have redirected with query or attempt state
                },
            },
        );
    };

    const handleSelectOption = (questionId: number, option: string) => {
        setAnswers((prev) => ({
            ...prev,
            [questionId]: option,
        }));
    };

    const handleSubmitAttempt = (e: React.FormEvent) => {
        e.preventDefault();
        if (!attemptId) return;

        setIsSubmitting(true);
        router.post(
            `/assessment/attempt/${attemptId}/submit`,
            { answers },
            {
                onFinish: () => setIsSubmitting(false),
            },
        );
    };

    return (
        <PublicLayout>
            <Head title={`${assessment.title} — BrightLearners`} />

            <div className="mx-auto max-w-4xl space-y-8 px-4 py-10 sm:px-6 lg:px-8">
                {/* Header Card */}
                <div className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-900 via-primary to-emerald-800 p-8 text-white shadow-xl">
                    <div className="relative z-10 space-y-3">
                        <div className="inline-flex items-center gap-2 rounded-full bg-white/20 px-3.5 py-1 text-xs font-semibold backdrop-blur-md">
                            <Sparkles className="h-3.5 w-3.5 text-amber-300" />
                            {assessment.class_name} • {assessment.subject_name}
                        </div>
                        <h1 className="text-3xl font-extrabold tracking-tight sm:text-4xl">
                            {assessment.title}
                        </h1>
                        <p className="max-w-2xl text-sm leading-relaxed text-white/90 sm:text-base">
                            {assessment.description ||
                                'A gentle informal check designed to identify learning confidence and tailor practice recommendations.'}
                        </p>
                    </div>
                </div>

                {/* Friendly Disclaimer Banner */}
                <div className="flex items-start gap-3.5 rounded-2xl border border-sky-200 bg-sky-50/70 p-4 text-sky-900 dark:border-sky-900/50 dark:bg-sky-950/30 dark:text-sky-200">
                    <Info className="mt-0.5 h-5 w-5 shrink-0 text-sky-600 dark:text-sky-400" />
                    <div className="text-xs leading-relaxed sm:text-sm">
                        <span className="font-semibold">
                            Informal Learning Check:
                        </span>{' '}
                        This is designed as a calm, diagnostic guide for parents
                        to discover strengths and areas needing practice. It is
                        not an exam or certification.
                    </div>
                </div>

                {/* Profile selection step if no attempt created yet */}
                {!attemptId ? (
                    <div className="space-y-6 rounded-2xl border bg-card p-6 shadow-sm sm:p-8">
                        <div>
                            <h2 className="text-xl font-bold">
                                Select Learner Profile
                            </h2>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Choose which child is taking this learning check
                                so results map to their progress profile.
                            </p>
                        </div>

                        {profiles.length > 0 ? (
                            <div className="grid gap-3 sm:grid-cols-2">
                                {profiles.map((p) => {
                                    const isSelected =
                                        selectedProfileId === p.id;
                                    return (
                                        <button
                                            key={p.id}
                                            type="button"
                                            onClick={() =>
                                                setSelectedProfileId(p.id)
                                            }
                                            className={`flex items-center justify-between rounded-xl border p-4 text-left transition-all ${
                                                isSelected
                                                    ? 'border-primary bg-primary/5 shadow-sm ring-2 ring-primary'
                                                    : 'hover:border-muted-foreground/30'
                                            }`}
                                        >
                                            <div>
                                                <div className="text-base font-semibold">
                                                    {p.nickname}
                                                </div>
                                                <div className="mt-0.5 text-xs text-muted-foreground">
                                                    Learner Profile
                                                </div>
                                            </div>
                                            {isSelected && (
                                                <CheckCircle2 className="h-5 w-5 text-primary" />
                                            )}
                                        </button>
                                    );
                                })}
                            </div>
                        ) : (
                            <div className="rounded-xl border border-dashed p-6 text-center text-sm text-muted-foreground">
                                Please sign in with your parent account to
                                record results for your child.
                            </div>
                        )}

                        <div className="flex justify-end pt-2">
                            <Button
                                size="lg"
                                onClick={handleStartAttempt}
                                disabled={!selectedProfileId}
                                className="gap-2"
                            >
                                Start Learning Check
                                <ArrowRight className="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                ) : (
                    /* Active Attempt Form */
                    <form onSubmit={handleSubmitAttempt} className="space-y-8">
                        {/* Progress Bar */}
                        <div className="sticky top-20 z-20 flex items-center justify-between gap-4 rounded-2xl border bg-background/95 p-4 shadow-sm backdrop-blur">
                            <div className="text-sm font-medium">
                                Answered{' '}
                                <span className="font-bold text-primary">
                                    {answeredCount}
                                </span>{' '}
                                of {allQuestions.length} questions
                            </div>
                            <div className="h-2 w-1/3 overflow-hidden rounded-full bg-muted">
                                <div
                                    className="h-full bg-primary transition-all duration-300"
                                    style={{
                                        width: `${allQuestions.length > 0 ? (answeredCount / allQuestions.length) * 100 : 0}%`,
                                    }}
                                />
                            </div>
                        </div>

                        {assessment.sections.map((section, sIndex) => (
                            <div key={section.id} className="space-y-5">
                                <div className="border-b pb-2">
                                    <h2 className="text-lg font-bold text-foreground">
                                        Section {sIndex + 1}: {section.title}
                                    </h2>
                                </div>

                                <div className="space-y-6">
                                    {section.questions.map(
                                        (question, qIndex) => {
                                            const selectedAnswer =
                                                answers[question.id];

                                            return (
                                                <div
                                                    key={question.id}
                                                    className="space-y-4 rounded-2xl border bg-card p-6 shadow-sm"
                                                >
                                                    <div className="flex items-start justify-between gap-3">
                                                        <div className="flex items-center gap-2">
                                                            <span className="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-xs font-bold text-primary">
                                                                {qIndex + 1}
                                                            </span>
                                                            <span className="text-xs font-medium tracking-wider text-muted-foreground uppercase">
                                                                {
                                                                    question.difficulty
                                                                }
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <p className="text-base leading-relaxed font-medium text-foreground sm:text-lg">
                                                        {question.prompt}
                                                    </p>

                                                    {/* Options */}
                                                    <div className="grid gap-2.5 pt-2 sm:grid-cols-2">
                                                        {question.options.map(
                                                            (
                                                                option,
                                                                optIdx,
                                                            ) => {
                                                                const isSelected =
                                                                    selectedAnswer ===
                                                                    option;
                                                                return (
                                                                    <button
                                                                        key={
                                                                            optIdx
                                                                        }
                                                                        type="button"
                                                                        onClick={() =>
                                                                            handleSelectOption(
                                                                                question.id,
                                                                                option,
                                                                            )
                                                                        }
                                                                        className={`flex items-center justify-between rounded-xl border p-4 text-left transition-all ${
                                                                            isSelected
                                                                                ? 'border-primary bg-primary/10 font-semibold text-primary ring-2 ring-primary'
                                                                                : 'hover:border-foreground/20 hover:bg-muted/50'
                                                                        }`}
                                                                    >
                                                                        <span>
                                                                            {
                                                                                option
                                                                            }
                                                                        </span>
                                                                        {isSelected && (
                                                                            <CheckCircle2 className="h-4 w-4 shrink-0 text-primary" />
                                                                        )}
                                                                    </button>
                                                                );
                                                            },
                                                        )}
                                                    </div>
                                                </div>
                                            );
                                        },
                                    )}
                                </div>
                            </div>
                        ))}

                        <div className="flex justify-end pt-4 pb-12">
                            <Button
                                type="submit"
                                size="lg"
                                disabled={isSubmitting || answeredCount === 0}
                                className="gap-2 px-8"
                            >
                                {isSubmitting
                                    ? 'Calculating Results...'
                                    : 'Complete & See Skill Results'}
                                <ArrowRight className="h-4 w-4" />
                            </Button>
                        </div>
                    </form>
                )}
            </div>
        </PublicLayout>
    );
}
