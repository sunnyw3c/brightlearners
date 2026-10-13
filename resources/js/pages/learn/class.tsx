import { Head, Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
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
    const characterIllustration =
        schoolClass.slug === 'class-1'
            ? '/images/class-1-girl.png'
            : schoolClass.slug === 'class-3'
              ? '/images/class-3-student.png'
              : '/images/class-2-boy.png';

    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
                {seo.noindex && <meta name="robots" content="noindex" />}
            </Head>

            <div className="mx-auto max-w-6xl space-y-10 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Banner with Character Artwork matching mockup */}
                <header className="relative flex flex-col items-center justify-between overflow-hidden rounded-3xl border border-sky-100 bg-linear-to-r from-sky-50 via-teal-50/40 to-blue-50/60 p-8 sm:flex-row sm:p-10 shadow-xs dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-900">
                    <div className="z-10 max-w-xl space-y-3 text-center sm:text-left">
                        <h1 className="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                            {schoolClass.name} Learning Resources
                        </h1>
                        <p className="text-base text-slate-600 sm:text-lg dark:text-slate-300">
                            {schoolClass.intro ??
                                'Free worksheets, activities and workbooks to help your child build strong concepts and confidence.'}
                        </p>
                    </div>

                    <div className="relative mt-6 shrink-0 sm:mt-0">
                        <div className="relative h-32 w-32 sm:h-40 sm:w-40 transition-transform duration-300 hover:scale-105">
                            <img
                                src={characterIllustration}
                                alt={`${schoolClass.name} cartoon character`}
                                className="h-full w-full object-contain drop-shadow-md"
                            />
                        </div>
                    </div>
                </header>

                {/* Filter Chips */}
                <section>
                    <SubjectChips
                        classSlug={schoolClass.slug}
                        subjects={subjects}
                    />
                </section>

                {/* Popular Topics Section */}
                {popularTopics.length > 0 && (
                    <section className="space-y-5">
                        <div className="flex items-center justify-between">
                            <h2 className="text-2xl font-bold tracking-tight text-foreground">
                                Popular Topics
                            </h2>
                            <Link
                                href={`/learn`}
                                className="inline-flex items-center gap-1 text-sm font-bold text-primary hover:underline"
                            >
                                View All Topics
                                <ArrowRight className="size-4" />
                            </Link>
                        </div>
                        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
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

                {/* Featured Free Resources Section */}
                <section className="space-y-5 pb-16">
                    <div className="flex items-center justify-between">
                        <h2 className="text-2xl font-bold tracking-tight text-foreground">
                            Featured Free Resources
                        </h2>
                        <Link
                            href={freeIndex()}
                            className="inline-flex items-center gap-1 text-sm font-bold text-primary hover:underline"
                        >
                            View All Free Resources
                            <ArrowRight className="size-4" />
                        </Link>
                    </div>
                    {featuredResources.length > 0 ? (
                        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
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
