import { Head, Link } from '@inertiajs/react';
import { ArrowRight, Sparkles } from 'lucide-react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { EmptyState } from '@/components/public/empty-state';
import { ResourceCard } from '@/components/public/resource-card';
import { SubjectChips } from '@/components/public/subject-chips';
import { TopicCard } from '@/components/public/topic-card';
import PublicLayout from '@/layouts/public-layout';
import { index as freeIndex } from '@/routes/free';
import type {
    BreadcrumbData,
    ResourceCardData,
    SeoProps,
    SubjectSummary,
} from '@/types/public';

type SchoolClass = {
    id: number;
    name: string;
    slug: string;
    intro: string | null;
};

type Topic = {
    id: number;
    name: string;
    slug: string;
    resource_count: number;
    subject_id: number;
};

type Props = {
    schoolClass: SchoolClass;
    subjects: SubjectSummary[];
    popularTopics: Topic[];
    featuredResources: ResourceCardData[];
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

export default function ClassPage({
    schoolClass,
    subjects,
    popularTopics,
    featuredResources,
    breadcrumbs,
    seo,
}: Props) {
    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
                {seo.noindex && <meta name="robots" content="noindex" />}
            </Head>

            <div className="page-container space-y-10 py-6 sm:py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <header className="relative isolate flex min-h-60 flex-col justify-center overflow-hidden rounded-2xl bg-[#edf9ff] p-7 sm:p-10">
                    <img
                        src="/images/class-learning-banner.webp"
                        width={1600}
                        height={667}
                        alt="A young learner reading a book"
                        className="absolute inset-0 h-full w-full object-cover object-[68%_center]"
                    />
                    <div className="absolute inset-0 bg-linear-to-r from-[#edf9ff] via-[#edf9ff]/95 to-transparent sm:via-[#edf9ff]/80 lg:via-[#edf9ff]/40" />
                    <div className="relative max-w-3xl space-y-3 pr-0 sm:pr-56">
                        <span className="eyebrow">
                            <Sparkles className="size-3.5" /> Curated practice
                            for young learners
                        </span>
                        <h1 className="text-3xl font-black tracking-[-0.035em] sm:text-4xl">
                            {schoolClass.name} Learning Resources
                        </h1>
                        {schoolClass.intro && (
                            <p className="max-w-2xl leading-7 text-foreground/60">
                                {schoolClass.intro}
                            </p>
                        )}
                    </div>
                </header>

                <SubjectChips
                    classSlug={schoolClass.slug}
                    subjects={subjects}
                />

                {popularTopics.length > 0 && (
                    <section className="space-y-5">
                        <div className="flex items-end justify-between gap-4">
                            <div>
                                <p className="mb-1 text-sm font-bold text-primary">
                                    Choose a skill area
                                </p>
                                <h2 className="section-title">
                                    Popular Topics
                                </h2>
                            </div>
                        </div>
                        <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5">
                            {popularTopics.map((topic, index) => {
                                const subject = subjects.find(
                                    (candidate) =>
                                        candidate.id === topic.subject_id,
                                );

                                return subject ? (
                                    <TopicCard
                                        key={topic.id}
                                        classSlug={schoolClass.slug}
                                        subjectSlug={subject.slug}
                                        topic={topic}
                                        index={index}
                                    />
                                ) : null;
                            })}
                        </div>
                    </section>
                )}

                <section className="space-y-5 pb-8">
                    <div className="flex items-end justify-between gap-4">
                        <div>
                            <p className="mb-1 text-sm font-bold text-success">
                                Print. Practise. Progress.
                            </p>
                            <h2 className="section-title">
                                Featured Free Resources
                            </h2>
                        </div>
                        <Link
                            href={freeIndex()}
                            className="hidden items-center gap-1 text-sm font-bold text-primary hover:gap-2 sm:inline-flex"
                        >
                            View all <ArrowRight className="size-4" />
                        </Link>
                    </div>
                    {featuredResources.length > 0 ? (
                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {featuredResources.map((resource) => (
                                <ResourceCard
                                    key={resource.id}
                                    resource={resource}
                                />
                            ))}
                        </div>
                    ) : (
                        <EmptyState
                            title="No free resources yet for this class"
                            description="Check back soon, or browse another class."
                        />
                    )}
                </section>
            </div>
        </PublicLayout>
    );
}
