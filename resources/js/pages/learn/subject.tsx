import { Head } from '@inertiajs/react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { ResourceCard } from '@/components/public/resource-card';
import { TopicCard } from '@/components/public/topic-card';
import PublicLayout from '@/layouts/public-layout';
import { subjectHref } from '@/lib/public-urls';
import type {
    BreadcrumbData,
    Paginated,
    ResourceCardData,
    SeoProps,
} from '@/types/public';

type SchoolClass = { id: number; name: string; slug: string };
type Subject = { id: number; name: string; slug: string };
type Topic = { id: number; name: string; slug: string };

type Props = {
    schoolClass: SchoolClass;
    subject: Subject;
    intro: string | null;
    topics: Topic[];
    resources: Paginated<ResourceCardData>;
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

export default function SubjectPage({
    schoolClass,
    subject,
    intro,
    topics,
    resources,
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

                <header className="space-y-2">
                    <h1 className="text-2xl font-semibold">
                        {schoolClass.name} {subject.name}
                    </h1>
                    {intro && (
                        <p className="max-w-2xl text-muted-foreground">
                            {intro}
                        </p>
                    )}
                </header>

                {topics.length > 0 && (
                    <section className="space-y-4">
                        <h2 className="text-xl font-semibold">Topics</h2>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {topics.map((topic, index) => (
                                <TopicCard
                                    key={topic.id}
                                    classSlug={schoolClass.slug}
                                    subjectSlug={subject.slug}
                                    topic={topic}
                                    index={index}
                                />
                            ))}
                        </div>
                    </section>
                )}

                <section className="space-y-4">
                    <h2 className="text-xl font-semibold">Resources</h2>
                    {resources.data.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                {resources.data.map((resource) => (
                                    <ResourceCard
                                        key={resource.id}
                                        resource={resource}
                                    />
                                ))}
                            </div>
                            <Pagination
                                currentPage={resources.current_page}
                                lastPage={resources.last_page}
                                hrefForPage={(page) =>
                                    `${subjectHref(schoolClass.slug, subject.slug)}?page=${page}`
                                }
                            />
                        </>
                    ) : (
                        <EmptyState
                            title="No free resources yet for this subject"
                            description="Check back soon, or browse another subject."
                        />
                    )}
                </section>
            </div>
        </PublicLayout>
    );
}
