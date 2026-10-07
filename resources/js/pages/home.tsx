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
        description: 'Free & premium practice',
        href: freeIndex.url({ query: { type: 'worksheet' } }),
        icon: FileText,
        tint: 'bg-emerald-50 text-emerald-600',
        accent: 'group-hover:border-emerald-200',
    },
    {
        title: 'Activities',
        description: 'Playful, engaging learning',
        href: freeIndex.url({ query: { type: 'activity' } }),
        icon: Sparkles,
        tint: 'bg-rose-50 text-rose-500',
        accent: 'group-hover:border-rose-200',
    },
    {
        title: 'Workbooks',
        description: 'Practice & mastery',
        href: shopIndex().url,
        icon: BookOpen,
        tint: 'bg-violet-50 text-violet-600',
        accent: 'group-hover:border-violet-200',
    },
    {
        title: 'Weekly Programmes',
        description: 'Structured learning',
        href: null,
        icon: CalendarCheck,
        tint: 'bg-amber-50 text-amber-600',
        accent: '',
    },
];

const classTaglines = [
    'Build strong foundations',
    'Explore with confidence',
    'Grow skills for tomorrow',
];

export default function Home({ classes, featuredResources, seo }: Props) {
    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
                {seo.noindex && <meta name="robots" content="noindex" />}
            </Head>

            <section className="page-container">
                <div className="overflow-hidden rounded-b-[1.75rem] bg-[#eaf7ff]">
                    <div className="grid lg:min-h-[30rem] lg:grid-cols-[0.9fr_1.1fr]">
                        <div className="relative z-10 flex flex-col justify-center px-6 py-9 sm:px-10 sm:py-11 lg:px-12 lg:py-12">
                            <span className="eyebrow w-fit">
                                <Sparkles className="size-3.5" />
                                Free learning resources for every child
                            </span>

                            <h1 className="mt-4 max-w-[34rem] text-[2.65rem] leading-[1.04] font-black tracking-[-0.045em] text-balance sm:text-5xl lg:text-[3.25rem]">
                                Make Learning{' '}
                                <span className="text-warm">Simple,</span>{' '}
                                <span className="text-primary">Fun</span> and
                                Meaningful
                            </h1>

                            <p className="mt-4 max-w-md text-[0.95rem] leading-7 text-foreground/65">
                                Free worksheets, engaging activities, workbooks
                                and guided programmes for Classes 1 to 3.
                            </p>

                            <div className="mt-6 flex flex-col gap-3 sm:flex-row">
                                <Button asChild size="lg">
                                    <Link href={freeIndex()}>
                                        Explore Free Resources
                                        <ArrowRight className="size-4" />
                                    </Link>
                                </Button>
                                <Button asChild size="lg" variant="outline">
                                    <Link href={shopIndex()}>
                                        Browse Workbooks
                                    </Link>
                                </Button>
                            </div>
                        </div>

                        <div className="relative min-h-[21rem] overflow-hidden sm:min-h-[27rem] lg:min-h-full">
                            <img
                                src="/images/brightlearners-hero-v2.webp"
                                width={1400}
                                height={933}
                                alt="A young learner enjoying an activity in her workbook"
                                fetchPriority="high"
                                className="absolute inset-0 h-full w-full scale-[1.08] object-cover object-[68%_center] sm:scale-100 lg:object-[62%_center]"
                            />
                            <div className="absolute inset-y-0 left-0 hidden w-24 bg-linear-to-r from-[#eaf7ff] to-transparent lg:block" />
                            <div className="absolute right-5 bottom-5 flex size-24 rotate-[-4deg] flex-col items-center justify-center rounded-full bg-[#ffd65a] text-center text-[0.72rem] leading-tight font-black text-[#102349] shadow-[0_12px_28px_-12px_rgba(15,35,80,0.45)] ring-4 ring-white/35 sm:right-7 sm:bottom-7 sm:size-26 sm:text-[0.8rem]">
                                <span>Small Steps</span>
                                <span>Big Learners</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section className="page-container py-4 sm:py-5">
                <div className="grid grid-cols-2 gap-2.5 rounded-2xl bg-white p-2.5 shadow-[0_14px_45px_-32px_rgba(15,35,80,0.42)] sm:grid-cols-4 sm:p-3">
                    {categoryCards.map((card) => {
                        const CardIcon = card.icon;
                        const content = (
                            <div
                                className={`flex h-full flex-col items-center gap-2.5 rounded-xl border border-transparent p-2.5 text-center transition-all sm:p-3 ${card.accent}`}
                            >
                                <span
                                    className={`flex size-10 shrink-0 items-center justify-center rounded-xl ${card.tint}`}
                                >
                                    <CardIcon className="size-5" />
                                </span>
                                <div className="min-w-0">
                                    <p className="text-sm font-extrabold sm:text-base">
                                        {card.title}
                                    </p>
                                    <p className="mt-0.5 text-xs leading-4 text-muted-foreground">
                                        {card.description}
                                    </p>
                                </div>
                            </div>
                        );

                        return card.href ? (
                            <Link
                                key={card.title}
                                href={card.href}
                                className="group"
                            >
                                {content}
                            </Link>
                        ) : (
                            <div
                                key={card.title}
                                className="opacity-55"
                                title="Coming soon"
                            >
                                {content}
                            </div>
                        );
                    })}
                </div>
            </section>

            {classes.length > 0 && (
                <section className="page-container py-7 sm:py-8">
                    <div className="mb-5 flex items-end justify-between gap-4">
                        <div>
                            <p className="mb-2 text-sm font-bold text-primary">
                                Find the right level
                            </p>
                            <h2 className="section-title">Browse by Class</h2>
                        </div>
                        <Link
                            href={freeIndex()}
                            className="hidden items-center gap-1 text-sm font-bold text-primary hover:gap-2 sm:inline-flex"
                        >
                            View all resources <ArrowRight className="size-4" />
                        </Link>
                    </div>
                    <div className="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        {classes.map((schoolClass, index) => (
                            <ClassCard
                                key={schoolClass.id}
                                schoolClass={schoolClass}
                                index={index}
                                tagline={
                                    classTaglines[index % classTaglines.length]
                                }
                            />
                        ))}
                    </div>
                </section>
            )}

            {featuredResources.length > 0 && (
                <section className="border-y border-primary/8 bg-white/55 py-16 sm:py-20">
                    <div className="page-container">
                        <div className="mb-7 flex items-end justify-between gap-4">
                            <div>
                                <p className="mb-2 text-sm font-bold text-success">
                                    Ready to print and use
                                </p>
                                <h2 className="section-title">
                                    Featured Free Resources
                                </h2>
                            </div>
                            <Link
                                href={freeIndex()}
                                className="hidden items-center gap-1 text-sm font-bold text-primary hover:gap-2 sm:inline-flex"
                            >
                                View all free resources{' '}
                                <ArrowRight className="size-4" />
                            </Link>
                        </div>
                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {featuredResources.map((resource) => (
                                <ResourceCard
                                    key={resource.id}
                                    resource={resource}
                                />
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </PublicLayout>
    );
}
