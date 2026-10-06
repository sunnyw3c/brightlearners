import { Head, Link } from '@inertiajs/react';
import { BookOpen, CalendarCheck, FileText, Sparkles } from 'lucide-react';
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
        tint: 'bg-primary/10 text-primary',
    },
    {
        title: 'Activities',
        description: 'Engaging Learning',
        href: freeIndex.url({ query: { type: 'activity' } }),
        icon: Sparkles,
        tint: 'bg-warm/10 text-warm',
    },
    {
        title: 'Workbooks',
        description: 'Practice & Mastery',
        href: shopIndex().url,
        icon: BookOpen,
        tint: 'bg-violet-500/10 text-violet-600',
    },
    {
        title: 'Weekly Programmes',
        description: 'Structured Learning',
        href: null,
        icon: CalendarCheck,
        tint: 'bg-orange-500/10 text-orange-600',
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

            <section className="mx-auto grid max-w-6xl items-center gap-10 px-4 py-14 lg:grid-cols-2 lg:py-20">
                <div className="space-y-6 text-center lg:text-left">
                    <span className="inline-flex items-center gap-1.5 rounded-full bg-highlight/20 px-3 py-1 text-xs font-semibold text-highlight-foreground">
                        <Sparkles className="size-3.5" />
                        Free Learning Resources for Every Child
                    </span>

                    <h1 className="text-4xl font-extrabold tracking-tight text-balance sm:text-5xl">
                        Make Learning <span className="text-warm">
                            Simple,
                        </span>{' '}
                        <span className="text-primary">Fun</span> and Meaningful
                    </h1>

                    <p className="mx-auto max-w-xl text-lg text-muted-foreground lg:mx-0">
                        Free worksheets, engaging activities, workbooks and
                        guided programmes for Classes 1 to 3.
                    </p>

                    <div className="flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                        <Button asChild size="lg">
                            <Link href={freeIndex()}>
                                Explore Free Resources
                            </Link>
                        </Button>
                        <Button asChild size="lg" variant="outline">
                            <Link href={shopIndex()}>Browse Workbooks</Link>
                        </Button>
                    </div>
                </div>

                <div className="relative mx-auto aspect-square w-full max-w-sm">
                    <div className="absolute inset-0 rounded-[2.5rem] bg-linear-to-br from-primary/15 via-highlight/15 to-warm/10" />
                    <div className="absolute inset-6 flex items-center justify-center rounded-4xl border bg-card shadow-sm">
                        <BookOpen className="size-20 text-primary/70" />
                    </div>
                    <div className="absolute -top-3 -right-3 rotate-6 rounded-2xl bg-highlight px-4 py-2 text-center text-xs font-bold text-highlight-foreground shadow-md">
                        Small Steps
                        <br />
                        Big Learners
                    </div>
                </div>
            </section>

            <section className="mx-auto max-w-6xl px-4 py-8">
                <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    {categoryCards.map((card) => {
                        const CardIcon = card.icon;
                        const content = (
                            <div className="flex h-full flex-col gap-3 rounded-2xl border bg-card p-5 shadow-sm transition-shadow hover:shadow-md">
                                <span
                                    className={`flex size-10 items-center justify-center rounded-xl ${card.tint}`}
                                >
                                    <CardIcon className="size-5" />
                                </span>
                                <div>
                                    <p className="font-semibold">
                                        {card.title}
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        {card.description}
                                    </p>
                                </div>
                            </div>
                        );

                        return card.href ? (
                            <Link key={card.title} href={card.href}>
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

            {classes.length > 0 && (
                <section className="mx-auto max-w-6xl px-4 py-8">
                    <h2 className="mb-4 text-2xl font-semibold">
                        Browse by Class
                    </h2>
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        {classes.map((schoolClass, index) => (
                            <ClassCard
                                key={schoolClass.id}
                                schoolClass={schoolClass}
                                index={index}
                            />
                        ))}
                    </div>
                </section>
            )}

            {featuredResources.length > 0 && (
                <section className="mx-auto max-w-6xl px-4 py-8 pb-16">
                    <h2 className="mb-4 text-2xl font-semibold">
                        Featured Free Resources
                    </h2>
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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
