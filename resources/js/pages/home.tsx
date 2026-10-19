import { Link } from '@inertiajs/react';
import { Seo } from '@/components/seo';
import {
    ArrowRight,
    BookOpen,
    CalendarCheck,
    CheckCircle2,
    Download,
    FileText,
    Sparkles,
    Star,
} from 'lucide-react';
import { ClassCard } from '@/components/public/class-card';
import { ResourceCard } from '@/components/public/resource-card';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { classHref } from '@/lib/public-urls';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';
import type {
    ResourceCardData,
    SchoolClassSummary,
    SeoProps,
} from '@/types/public';

type Props = {
    classes: SchoolClassSummary[];
    featuredResources: ResourceCardData[];
    seo: SeoProps;
};

const categoryCards = [
    {
        title: 'Worksheets',
        description: 'Free & Premium',
        href: freeIndex.url({ query: { type: 'worksheet' } }),
        icon: FileText,
        tint: 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400',
    },
    {
        title: 'Activities',
        description: 'Engaging Learning',
        href: freeIndex.url({ query: { type: 'activity' } }),
        icon: Sparkles,
        tint: 'bg-rose-50 text-rose-500 border-rose-100 dark:bg-rose-950/40 dark:text-rose-400',
    },
    {
        title: 'Workbooks',
        description: 'Practice & Mastery',
        href: shopIndex().url,
        icon: BookOpen,
        tint: 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/40 dark:text-purple-400',
    },
    {
        title: 'Weekly Programmes',
        description: 'Structured Learning',
        href: '/membership',
        icon: CalendarCheck,
        tint: 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400',
    },
];

export default function Home({ classes, featuredResources, seo }: Props) {
    const quickGradeClasses = [
        {
            name: 'Class 1',
            slug: 'class-1',
            pillClass:
                'hover:border-amber-400 hover:bg-amber-50/90 text-amber-900 bg-amber-50 border-amber-200/80 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-900/50',
        },
        {
            name: 'Class 2',
            slug: 'class-2',
            pillClass:
                'hover:border-teal-400 hover:bg-teal-50/90 text-teal-900 bg-teal-50 border-teal-200/80 dark:bg-teal-950/40 dark:text-teal-300 dark:border-teal-900/50',
        },
        {
            name: 'Class 3',
            slug: 'class-3',
            pillClass:
                'hover:border-blue-400 hover:bg-blue-50/90 text-blue-900 bg-blue-50 border-blue-200/80 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-900/50',
        },
    ];

    return (
        <PublicLayout>
            <Seo seo={seo} />

            {/* Hero Section */}
            <section className="relative overflow-hidden bg-linear-to-b from-sky-50/70 via-background to-background pt-10 pb-14 sm:pt-14 sm:pb-18 lg:pt-18 lg:pb-22 dark:from-slate-900/60 dark:via-background dark:to-background">
                {/* Ambient Soft Glow Accents */}
                <div
                    aria-hidden="true"
                    className="pointer-events-none absolute -top-24 left-1/2 -z-10 h-[480px] w-[800px] -translate-x-1/2 rounded-full bg-linear-to-tr from-sky-200/40 via-amber-100/35 to-purple-200/35 blur-3xl dark:from-sky-950/30 dark:via-purple-950/30 dark:to-background"
                />
                <div
                    aria-hidden="true"
                    className="pointer-events-none absolute top-1/4 -right-16 -z-10 h-64 w-64 rounded-full bg-amber-200/30 blur-2xl dark:bg-amber-950/20"
                />
                <div
                    aria-hidden="true"
                    className="pointer-events-none absolute bottom-10 -left-16 -z-10 h-64 w-64 rounded-full bg-blue-200/30 blur-2xl dark:bg-blue-950/20"
                />

                {/* Subtle Playful Educational Background Shapes */}
                <div
                    aria-hidden="true"
                    className="pointer-events-none absolute inset-0 -z-10 overflow-hidden select-none"
                >
                    <span className="absolute top-10 left-[8%] text-2xl font-black text-amber-400/50 dark:text-amber-400/30">
                        ★
                    </span>
                    <span className="absolute top-24 right-[10%] text-xl font-bold text-sky-400/50 dark:text-sky-400/30">
                        ●
                    </span>
                    <span className="absolute top-2/3 left-[4%] text-3xl font-black text-rose-300/40 dark:text-rose-400/25">
                        +
                    </span>
                    <span className="absolute top-1/2 right-[5%] text-2xl font-black text-indigo-300/40 dark:text-indigo-400/25">
                        ÷
                    </span>
                    <span className="absolute right-[18%] bottom-12 text-lg font-black text-amber-400/40 dark:text-amber-400/25">
                        ▲
                    </span>
                </div>

                <div className="mx-auto grid max-w-6xl items-center gap-12 px-4 lg:grid-cols-12">
                    {/* Left Column: Headlines, Trust, and Calls to Action */}
                    <div className="space-y-6 text-center lg:col-span-7 lg:text-left">
                        {/* Shimmering Trust Badge */}
                        <div className="inline-flex items-center gap-2.5 rounded-full border border-sky-200/80 bg-white/95 px-4 py-1.5 text-xs font-bold text-blue-700 shadow-xs backdrop-blur-xs transition-all hover:bg-white dark:border-slate-700/80 dark:bg-slate-800/80 dark:text-sky-300">
                            <span className="relative flex size-2">
                                <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-500 opacity-75" />
                                <span className="relative inline-flex size-2 rounded-full bg-blue-600" />
                            </span>
                            <span>Free Learning Resources for Every Child</span>
                            <span className="rounded-full bg-blue-100/90 px-2 py-0.5 text-[10px] font-extrabold tracking-wide text-blue-800 uppercase dark:bg-blue-900/60 dark:text-blue-200">
                                Classes 1–3
                            </span>
                        </div>

                        {/* Bold Playful Main Headline */}
                        <h1 className="text-4xl font-black tracking-tight text-slate-900 sm:text-5xl lg:text-[3.75rem] lg:leading-[1.12] dark:text-white">
                            Make Learning{' '}
                            <span className="relative inline-block text-[#EF4444] dark:text-rose-400">
                                Simple,
                                <svg
                                    className="absolute -bottom-1.5 left-0 w-full text-[#EF4444]/30 dark:text-rose-400/40"
                                    viewBox="0 0 100 12"
                                    fill="none"
                                    preserveAspectRatio="none"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M2 9C25 3 75 3 98 9"
                                        stroke="currentColor"
                                        strokeWidth="4"
                                        strokeLinecap="round"
                                    />
                                </svg>
                            </span>{' '}
                            <span className="relative inline-block text-[#2563EB] dark:text-blue-400">
                                Fun
                                <span
                                    className="absolute -top-3.5 -right-3 text-lg font-bold text-[#FBBF24] select-none"
                                    aria-hidden="true"
                                >
                                    ✦
                                </span>
                            </span>{' '}
                            and Meaningful
                        </h1>

                        {/* Subheading */}
                        <p className="mx-auto max-w-xl text-lg leading-relaxed text-slate-600 sm:text-xl lg:mx-0 dark:text-slate-300">
                            Free printable worksheets, engaging activities,
                            workbooks and guided programmes for Classes 1 to 3.
                        </p>

                        {/* Feature / Trust Badges */}
                        <div className="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs font-semibold text-slate-600 sm:text-sm lg:justify-start dark:text-slate-300">
                            <span className="inline-flex items-center gap-1.5">
                                <CheckCircle2 className="size-4 shrink-0 text-emerald-500 dark:text-emerald-400" />
                                CBSE & ICSE Aligned
                            </span>
                            <span className="inline-flex items-center gap-1.5">
                                <CheckCircle2 className="size-4 shrink-0 text-emerald-500 dark:text-emerald-400" />
                                Printable PDFs & Answer Keys
                            </span>
                            <span className="inline-flex items-center gap-1.5">
                                <CheckCircle2 className="size-4 shrink-0 text-emerald-500 dark:text-emerald-400" />
                                100% Free to Start
                            </span>
                        </div>

                        {/* Action Buttons */}
                        <div className="flex flex-col sm:flex-row items-center justify-center gap-3 pt-1 sm:gap-4 lg:justify-start">
                            <Button
                                asChild
                                size="lg"
                                className="group h-13 w-full sm:w-auto rounded-full bg-linear-to-r from-[#2563EB] to-blue-700 px-8 text-base font-bold text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:scale-[1.02] hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-500/35"
                            >
                                <Link
                                    href={freeIndex()}
                                    className="inline-flex items-center justify-center gap-2"
                                >
                                    <span>Explore Free Resources</span>
                                    <ArrowRight className="size-4 transition-transform duration-300 group-hover:translate-x-1" />
                                </Link>
                            </Button>
                            <Button
                                asChild
                                size="lg"
                                variant="outline"
                                className="h-13 w-full sm:w-auto rounded-full border-slate-200 bg-white/90 px-8 text-base font-bold text-slate-800 shadow-xs backdrop-blur-xs transition-all duration-300 hover:scale-[1.02] hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900/90 dark:text-slate-100 dark:hover:bg-slate-800"
                            >
                                <Link
                                    href={shopIndex()}
                                    className="inline-flex items-center justify-center gap-2"
                                >
                                    <BookOpen className="size-4 text-purple-600 dark:text-purple-400" />
                                    <span>Browse Workbooks</span>
                                </Link>
                            </Button>
                        </div>

                        {/* Quick Class Jump Chips */}
                        <div className="flex flex-wrap items-center justify-center gap-2 pt-1 lg:justify-start">
                            <span className="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                Jump directly to:
                            </span>
                            {quickGradeClasses.map((grade) => (
                                <Link
                                    key={grade.slug}
                                    href={classHref(grade.slug)}
                                    className={`rounded-full border px-3 py-1 text-xs font-bold transition-all duration-200 hover:scale-105 ${grade.pillClass}`}
                                >
                                    {grade.name}
                                </Link>
                            ))}
                        </div>

                        {/* Social Proof / Parent Trust Row */}
                        <div className="flex items-center justify-center gap-3 pt-2 lg:justify-start">
                            <div className="flex -space-x-2 overflow-hidden">
                                <img
                                    src="/images/avatar-aarav.jpg"
                                    alt="Student Aarav"
                                    className="inline-block size-8 rounded-full object-cover ring-2 ring-white dark:ring-slate-900"
                                />
                                <img
                                    src="/images/avatar-myra.jpg"
                                    alt="Student Myra"
                                    className="inline-block size-8 rounded-full object-cover ring-2 ring-white dark:ring-slate-900"
                                />
                                <div className="flex size-8 items-center justify-center rounded-full bg-linear-to-tr from-blue-600 to-indigo-600 text-[10px] font-black text-white ring-2 ring-white dark:ring-slate-900">
                                    10k+
                                </div>
                            </div>
                            <div className="text-left text-xs">
                                <div className="flex items-center gap-1 text-amber-500">
                                    {[...Array(5)].map((_, i) => (
                                        <Star
                                            key={i}
                                            className="size-3.5 fill-amber-400 text-amber-400"
                                        />
                                    ))}
                                    <span className="ml-1 font-black text-slate-800 dark:text-slate-100">
                                        4.9/5
                                    </span>
                                </div>
                                <p className="font-medium text-slate-500 dark:text-slate-400">
                                    Loved by 10,000+ happy parents & teachers
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Hero Graphic with Floating Interactive Micro-Cards */}
                    <div className="relative mx-auto w-full max-w-md lg:col-span-5">
                        {/* Decorative Background Aura */}
                        <div
                            aria-hidden="true"
                            className="absolute -inset-4 -z-10 rounded-[3.5rem] bg-linear-to-tr from-amber-200/50 via-sky-200/50 to-purple-200/50 opacity-75 blur-xl dark:from-sky-900/40 dark:via-purple-900/40 dark:to-amber-900/40 dark:opacity-40"
                        />

                        {/* Main Image Card */}
                        <div className="relative aspect-square overflow-hidden rounded-[3rem] border-4 border-white/90 bg-linear-to-tr from-amber-100/70 via-sky-100/70 to-purple-100/70 p-3 shadow-2xl backdrop-blur-xs transition-shadow duration-500 hover:shadow-blue-500/15 dark:border-slate-700/80 dark:from-slate-800/80 dark:via-slate-800/90 dark:to-slate-800">
                            <div className="h-full w-full overflow-hidden rounded-[2.5rem] bg-amber-50/50 dark:bg-slate-900/50">
                                <img
                                    src="/images/hero-girl.jpg"
                                    alt="BrightLearners student learning happily"
                                    className="h-full w-full object-cover transition-transform duration-700 hover:scale-105"
                                    loading="eager"
                                />
                            </div>

                            {/* Floating Micro-Card Top Left: Printable PDF */}
                            <div className="absolute top-6 -left-2 rounded-2xl border border-white/80 bg-white/95 p-2.5 shadow-xl backdrop-blur-md transition-all duration-300 hover:scale-105 sm:-left-5 dark:border-slate-700/80 dark:bg-slate-900/95">
                                <div className="flex items-center gap-2.5">
                                    <span className="flex size-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950/70 dark:text-emerald-400">
                                        <Download className="size-4.5 stroke-[2.5]" />
                                    </span>
                                    <div className="pr-1 text-left">
                                        <p className="text-xs font-extrabold text-slate-900 dark:text-slate-100">
                                            Printable PDFs
                                        </p>
                                        <p className="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                                            With Answer Keys
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Floating Micro-Pill Bottom Left: 500+ Worksheets */}
                            <div className="absolute bottom-8 -left-1 rounded-full border border-white/80 bg-white/95 px-3 py-1.5 shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-105 sm:-left-3 dark:border-slate-700/80 dark:bg-slate-900/95">
                                <div className="flex items-center gap-1.5 text-xs font-bold text-slate-800 dark:text-slate-100">
                                    <Sparkles className="size-3.5 fill-amber-400 text-amber-500" />
                                    <span>500+ Free Worksheets</span>
                                </div>
                            </div>

                            {/* Floating "Small Steps Big Learners" Yellow Badge */}
                            <div className="absolute -right-1 -bottom-1 rotate-[-4deg] rounded-3xl bg-[#FBBF24] px-5 py-2.5 text-center shadow-xl ring-4 ring-white transition-transform duration-300 hover:scale-105 hover:rotate-0 dark:ring-slate-900">
                                <div className="flex items-center justify-center gap-1">
                                    <Star className="size-3 fill-amber-950 text-amber-950" />
                                    <span className="block text-xs font-black tracking-wide text-amber-950 uppercase">
                                        Small Steps
                                    </span>
                                </div>
                                <span className="block text-sm font-black text-amber-950">
                                    KiddoZen
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* 4 Category Cards matching mockup with elevated hover aesthetics */}
            <section className="mx-auto max-w-6xl px-4 py-8">
                <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    {categoryCards.map((card) => {
                        const CardIcon = card.icon;
                        const content = (
                            <div className="group relative flex h-full flex-col justify-between gap-4 overflow-hidden rounded-3xl border border-slate-200/80 bg-card p-5 shadow-xs transition-all duration-300 hover:-translate-y-1.5 hover:border-primary/40 hover:shadow-xl dark:border-slate-800">
                                <div className="flex items-start justify-between">
                                    <span
                                        className={`flex size-12 items-center justify-center rounded-2xl border transition-all duration-300 group-hover:scale-110 group-hover:shadow-md ${card.tint}`}
                                    >
                                        <CardIcon className="size-6 stroke-[2.2]" />
                                    </span>
                                    <span className="flex size-6 -translate-x-2 items-center justify-center rounded-full bg-slate-100/80 text-slate-400 opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:bg-primary/10 group-hover:text-primary group-hover:opacity-100 dark:bg-slate-800">
                                        <ArrowRight className="size-3.5 stroke-[2.5]" />
                                    </span>
                                </div>
                                <div>
                                    <p className="text-base font-bold text-foreground transition-colors group-hover:text-primary">
                                        {card.title}
                                    </p>
                                    <p className="mt-0.5 text-xs font-medium text-muted-foreground">
                                        {card.description}
                                    </p>
                                </div>
                            </div>
                        );

                        return card.href ? (
                            <Link
                                key={card.title}
                                href={card.href}
                                className="block focus:outline-hidden"
                            >
                                {content}
                            </Link>
                        ) : (
                            <div
                                key={card.title}
                                className="cursor-not-allowed opacity-60"
                                title="Coming soon"
                            >
                                {content}
                            </div>
                        );
                    })}
                </div>
            </section>

            {/* Browse by Class section matching mockup (Class 1, Class 2, Class 3) */}
            {classes.length > 0 &&
                (() => {
                    const prioritySlugs = ['class-1', 'class-2', 'class-3'];
                    const sorted = [...classes].sort((a, b) => {
                        const aIdx = prioritySlugs.indexOf(a.slug);
                        const bIdx = prioritySlugs.indexOf(b.slug);
                        if (aIdx !== -1 && bIdx !== -1) return aIdx - bIdx;
                        if (aIdx !== -1) return -1;
                        if (bIdx !== -1) return 1;
                        return 0;
                    });
                    const displayClasses = sorted.slice(0, 3);

                    return (
                        <section className="mx-auto max-w-6xl px-4 py-10">
                            <div className="mb-6 flex items-center justify-between">
                                <h2 className="text-2xl font-extrabold tracking-tight text-foreground sm:text-3xl">
                                    Browse by Class
                                </h2>
                            </div>
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                {displayClasses.map((schoolClass, index) => (
                                    <ClassCard
                                        key={schoolClass.id}
                                        schoolClass={schoolClass}
                                        index={index}
                                    />
                                ))}
                            </div>
                        </section>
                    );
                })()}

            {/* Featured Free Resources section */}
            {featuredResources.length > 0 && (
                <section className="mx-auto max-w-6xl px-4 py-10 pb-20">
                    <div className="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 className="text-2xl font-extrabold tracking-tight text-foreground sm:text-3xl">
                                Featured Free Resources
                            </h2>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Hand-picked printable worksheets and activities
                                for daily practice.
                            </p>
                        </div>
                        <Link
                            href={freeIndex()}
                            className="inline-flex shrink-0 items-center gap-1.5 text-sm font-bold text-primary hover:underline"
                        >
                            View All Free Resources
                            <ArrowRight className="size-4" />
                        </Link>
                    </div>
                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {featuredResources.map((resource) => (
                            <ResourceCard
                                key={resource.id}
                                resource={resource}
                            />
                        ))}
                    </div>
                </section>
            )}
        </PublicLayout>
    );
}
