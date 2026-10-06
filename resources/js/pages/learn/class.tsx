import { Head } from '@inertiajs/react';
import { BookOpen } from 'lucide-react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { EmptyState } from '@/components/public/empty-state';
import { ResourceCard } from '@/components/public/resource-card';
import { SubjectChips } from '@/components/public/subject-chips';
import { TopicCard } from '@/components/public/topic-card';
import PublicLayout from '@/layouts/public-layout';
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

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <header className="flex flex-col items-center gap-6 rounded-3xl bg-linear-to-br from-primary/10 via-highlight/10 to-warm/5 p-8 sm:flex-row sm:justify-between">
                    <div className="space-y-2 text-center sm:text-left">
                        <h1 className="text-3xl font-bold">
                            {schoolClass.name} Learning Resources
                        </h1>
                        {schoolClass.intro && (
                            <p className="max-w-2xl text-muted-foreground">
                                {schoolClass.intro}
                            </p>
                        )}
                    </div>
                    <span className="flex size-20 shrink-0 items-center justify-center rounded-full bg-card shadow-sm">
                        <BookOpen className="size-9 text-primary/70" />
                    </span>
                </header>

                <SubjectChips
                    classSlug={schoolClass.slug}
                    subjects={subjects}
                />

                {popularTopics.length > 0 && (
                    <section className="space-y-4">
                        <h2 className="text-xl font-semibold">
                            Popular Topics
                        </h2>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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

                <section className="space-y-4">
                    <h2 className="text-xl font-semibold">
                        Featured Free Resources
                    </h2>
                    {featuredResources.length > 0 ? (
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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
