import { Head } from '@inertiajs/react';
import { Shapes } from 'lucide-react';
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

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <header className="flex flex-col items-center gap-6 rounded-3xl bg-linear-to-br from-primary/10 via-highlight/10 to-warm/5 p-8 sm:flex-row sm:justify-between">
                    <div className="space-y-2 text-center sm:text-left">
                        <h1 className="text-3xl font-bold">
                            {schoolClass.name} {subject.name}: {topic.name}
                        </h1>
                        {intro && (
                            <p className="max-w-2xl text-muted-foreground">
                                {intro}
                            </p>
                        )}
                    </div>
                    <span className="flex size-20 shrink-0 items-center justify-center rounded-full bg-card shadow-sm">
                        <Shapes className="size-9 text-primary/70" />
                    </span>
                </header>

                {skills.length > 0 && (
                    <section className="space-y-2">
                        <h2 className="text-lg font-semibold">
                            Related skills
                        </h2>
                        <ul className="flex flex-wrap gap-2">
                            {skills.map((skill) => (
                                <li
                                    key={skill.id}
                                    className="rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary"
                                >
                                    {skill.name}
                                </li>
                            ))}
                        </ul>
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
