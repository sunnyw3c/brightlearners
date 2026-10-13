import { Head, Link } from '@inertiajs/react';
import {
    ArrowRight,
    BookOpen,
    CalendarCheck,
    FileText,
    Sparkles,
} from 'lucide-react';
import { ClassCard } from '@/components/public/class-card';
import { ResourceCard } from '@/components/public/resource-card';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
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
    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
                {seo.noindex && <meta name="robots" content="noindex" />}
            </Head>

            {/* Hero Section */}
            <section className="relative overflow-hidden bg-linear-to-b from-sky-50/50 via-background to-background py-12 lg:py-20">
                <div className="mx-auto grid max-w-6xl items-center gap-12 px-4 lg:grid-cols-12">
                    <div className="space-y-6 text-center lg:col-span-7 lg:text-left">
                        <div className="inline-flex items-center gap-2 rounded-full border border-sky-100 bg-sky-50 px-4 py-1.5 text-xs font-bold text-blue-600 shadow-xs dark:border-sky-900/50 dark:bg-sky-950/50 dark:text-sky-400">
                            <span className="flex size-2 rounded-full bg-blue-600 animate-pulse" />
                            Free Learning Resources for Every Child
                        </div>

                        <h1 className="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl dark:text-white">
                            Make Learning{' '}
                            <span className="text-[#EF4444]">Simple,</span>{' '}
                            <span className="text-[#2563EB]">Fun</span> and
                            Meaningful
                        </h1>

                        <p className="mx-auto max-w-xl text-lg leading-relaxed text-slate-600 lg:mx-0 dark:text-slate-300">
                            Free worksheets, engaging activities, workbooks and
                            guided programmes for Classes 1 to 3.
                        </p>

                        <div className="flex flex-wrap items-center justify-center gap-4 pt-2 lg:justify-start">
                            <Button
                                asChild
                                size="lg"
                                className="h-13 rounded-full bg-[#2563EB] px-8 text-base font-bold text-white shadow-lg shadow-blue-500/25 hover:bg-blue-700 hover:shadow-xl transition-all"
                            >
                                <Link href={freeIndex()}>
                                    Explore Free Resources
                                </Link>
                            </Button>
                            <Button
                                asChild
                                size="lg"
                                variant="outline"
                                className="h-13 rounded-full border-slate-200 bg-white px-8 text-base font-bold text-slate-800 shadow-xs hover:bg-slate-50 hover:border-slate-300 dark:bg-slate-900 dark:text-slate-100 dark:border-slate-800"
                            >
                                <Link href={shopIndex()}>Browse Workbooks</Link>
                            </Button>
                        </div>
                    </div>

                    {/* Hero Graphic matching mockup */}
                    <div className="relative mx-auto w-full max-w-md lg:col-span-5">
                        <div className="relative aspect-square overflow-hidden rounded-[3rem] border-4 border-white/80 bg-linear-to-tr from-amber-100/60 via-sky-100/60 to-purple-100/60 p-3 shadow-2xl dark:border-slate-800">
                            <div className="h-full w-full overflow-hidden rounded-[2.5rem]">
                                <img
                                    src="/images/hero-girl.jpg"
                                    alt="BrightLearners student learning happily"
                                    className="h-full w-full object-cover transition-transform duration-700 hover:scale-105"
                                    loading="eager"
                                />
                            </div>

                            {/* Floating "Small Steps Big Learners" yellow badge */}
                            <div className="absolute -bottom-1 -right-1 rotate-[-4deg] rounded-3xl bg-[#FBBF24] px-5 py-2.5 text-center shadow-xl ring-4 ring-white dark:ring-slate-900">
                                <span className="block text-xs font-black tracking-wide uppercase text-amber-950">
                                    Small Steps
                                </span>
                                <span className="block text-sm font-black text-amber-950">
                                    Big Learners
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* 4 Category Cards matching mockup */}
            <section className="mx-auto max-w-6xl px-4 py-8">
                <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    {categoryCards.map((card) => {
                        const CardIcon = card.icon;
                        const content = (
                            <div className="group flex h-full flex-col gap-3 rounded-3xl border border-slate-200/80 bg-card p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg dark:border-slate-800">
                                <span
                                    className={`flex size-12 items-center justify-center rounded-2xl border transition-transform duration-300 group-hover:scale-110 ${card.tint}`}
                                >
                                    <CardIcon className="size-6 stroke-[2.2]" />
                                </span>
                                <div>
                                    <p className="text-base font-bold text-foreground group-hover:text-primary transition-colors">
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
            {classes.length > 0 && (() => {
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
                    <div className="mb-6 flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-extrabold tracking-tight text-foreground sm:text-3xl">
                                Featured Free Resources
                            </h2>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Hand-picked printable worksheets and activities for daily practice.
                            </p>
                        </div>
                        <Link
                            href={freeIndex()}
                            className="inline-flex items-center gap-1.5 text-sm font-bold text-primary hover:underline"
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
