import { Head } from '@inertiajs/react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { ResourceCard } from '@/components/public/resource-card';
import PublicLayout from '@/layouts/public-layout';
import { topicHref } from '@/lib/public-urls';
import type {
    BreadcrumbData,
    Paginated,
    ResourceCardData,
    SeoProps,
} from '@/types/public';

type SchoolClass = { id: number; name: string; slug: string };
type Subject = { id: number; name: string; slug: string };
type Topic = { id: number; name: string; slug: string };
type Skill = { id: number; name: string; slug: string };

type Props = {
    schoolClass: SchoolClass;
    subject: Subject;
    topic: Topic;
    intro: string | null;
    skills: Skill[];
    resources: Paginated<ResourceCardData>;
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

export default function TopicPage({
    schoolClass,
    subject,
    topic,
    intro,
    skills,
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

            <div className="page-container space-y-10 py-6 sm:py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <header className="rounded-[2rem] border border-white/80 bg-linear-to-br from-amber-50 via-white to-sky-100 p-7 shadow-[0_20px_60px_-42px_rgba(15,35,80,0.48)] sm:p-10">
                    <p className="mb-2 text-sm font-bold text-primary">
                        Focused skill practice
                    </p>
                    <h1 className="text-3xl font-black tracking-[-0.035em] sm:text-4xl">
                        {schoolClass.name} {subject.name}: {topic.name}
                    </h1>
                    {intro && (
                        <p className="mt-3 max-w-2xl leading-7 text-muted-foreground">
                            {intro}
                        </p>
                    )}
                </header>

                {skills.length > 0 && (
                    <section className="space-y-3">
                        <h2 className="text-lg font-extrabold">
                            Related skills
                        </h2>
                        <ul className="flex flex-wrap gap-2">
                            {skills.map((skill) => (
                                <li
                                    key={skill.id}
                                    className="rounded-xl border border-primary/10 bg-primary/6 px-3 py-2 text-sm font-semibold text-primary"
                                >
                                    {skill.name}
                                </li>
                            ))}
                        </ul>
                    </section>
                )}

                <section className="space-y-5 pb-8">
                    <h2 className="section-title">Resources</h2>
                    {resources.data.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
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
                                    `${topicHref(schoolClass.slug, subject.slug, topic.slug)}?page=${page}`
                                }
                            />
                        </>
                    ) : (
                        <EmptyState
                            title="No free resources yet for this topic"
                            description="Check back soon, or browse another topic."
                        />
                    )}
                </section>
            </div>
        </PublicLayout>
    );
}
