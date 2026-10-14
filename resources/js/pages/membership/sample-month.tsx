import { Seo, type SeoProps } from '@/components/seo';
import PublicLayout from '@/layouts/public-layout';
import { Button } from '@/components/ui/button';
import { ArrowLeft, BookOpen, FileText, Sparkles } from 'lucide-react';

type SampleItem = {
    title: string;
    type: string;
    guidance: string;
};

type SampleWeek = {
    week_number: number;
    title: string;
    emphasis: string;
    items: SampleItem[];
};

type Props = {
    sample: {
        title: string;
        description: string;
        weeks: SampleWeek[];
    };
    seo: SeoProps;
};

export default function SampleMonth({ sample, seo }: Props) {
    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-5xl space-y-8 px-4 py-12 sm:px-6 lg:px-8">
                <div>
                    <Button asChild variant="outline" size="sm">
                        <a href="/membership">
                            <ArrowLeft className="mr-2 h-4 w-4" /> Back to
                            Membership Overview
                        </a>
                    </Button>
                </div>

                <header className="space-y-3">
                    <div className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                        <Sparkles className="h-3.5 w-3.5" />
                        <span>Curriculum Sample Preview</span>
                    </div>
                    <h1 className="text-3xl font-extrabold tracking-tight sm:text-4xl">
                        {sample.title}
                    </h1>
                    <p className="max-w-2xl text-base text-muted-foreground">
                        {sample.description}
                    </p>
                </header>

                <div className="space-y-8">
                    {sample.weeks.map((week) => (
                        <div
                            key={week.week_number}
                            className="space-y-6 rounded-3xl border bg-card p-6 shadow-sm sm:p-8"
                        >
                            <div className="flex flex-col justify-between gap-2 border-b pb-4 sm:flex-row sm:items-center">
                                <div className="space-y-1">
                                    <div className="flex items-center gap-2">
                                        <span className="inline-flex items-center rounded bg-primary px-2.5 py-0.5 text-xs font-semibold text-primary-foreground">
                                            Week {week.week_number}
                                        </span>
                                        <h2 className="text-xl font-bold">
                                            {week.title}
                                        </h2>
                                    </div>
                                    <p className="text-xs text-muted-foreground">
                                        <strong>Focus:</strong> {week.emphasis}
                                    </p>
                                </div>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-3">
                                {week.items.map((item, idx) => (
                                    <div
                                        key={idx}
                                        className="flex flex-col justify-between space-y-3 rounded-2xl border bg-muted/30 p-4"
                                    >
                                        <div className="space-y-2">
                                            <div className="flex items-center justify-between">
                                                <span className="rounded border bg-background px-2 py-0.5 text-xs font-medium text-foreground">
                                                    {item.type}
                                                </span>
                                                <FileText className="h-4 w-4 text-muted-foreground" />
                                            </div>
                                            <h3 className="text-sm leading-snug font-semibold">
                                                {item.title}
                                            </h3>
                                        </div>

                                        <div className="space-y-1 rounded-xl border bg-background p-2.5 text-xs text-muted-foreground">
                                            <span className="flex items-center gap-1 text-[11px] font-semibold text-foreground">
                                                <BookOpen className="h-3 w-3 text-primary" />{' '}
                                                Parent Guidance:
                                            </span>
                                            <p className="line-clamp-2">
                                                {item.guidance}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>

                <div className="space-y-4 rounded-3xl border border-primary/20 bg-primary/5 p-8 text-center">
                    <h2 className="text-2xl font-bold">
                        Ready to Start Your Child’s Learning Routine?
                    </h2>
                    <p className="mx-auto max-w-xl text-sm text-muted-foreground">
                        Join our 90-day family pilot for Class 1, 2, or 3. One
                        single prepaid payment of ₹499 for 3 full months.
                    </p>
                    <Button asChild size="lg" className="rounded-full px-8">
                        <a href="/membership">Join the 90-Day Pilot</a>
                    </Button>
                </div>
            </div>
        </PublicLayout>
    );
}
