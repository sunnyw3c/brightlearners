import { Head } from '@inertiajs/react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { classHref, subjectHref, topicHref } from '@/lib/public-urls';
import PublicLayout from '@/layouts/public-layout';
import { Link } from '@inertiajs/react';
import type { BreadcrumbData, SeoProps } from '@/types/public';

type Skill = {
    id: number;
    name: string;
    slug: string;
    learning_objective: string;
};

type Topic = {
    id: number;
    name: string;
    slug: string;
    skills: Skill[];
};

type Subject = {
    id: number;
    name: string;
    slug: string;
    topics: Topic[];
};

type SchoolClass = {
    id: number;
    name: string;
    slug: string;
    subjects: Subject[];
};

type Props = {
    classes: SchoolClass[];
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

export default function LearnIndex({ classes, breadcrumbs, seo }: Props) {
    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
            </Head>

            <div className="page-container space-y-9 py-7 sm:py-10">
                <PublicBreadcrumbs items={breadcrumbs} />

                <header className="rounded-[2rem] border border-white/80 bg-linear-to-br from-blue-50 via-white to-amber-50 p-7 shadow-[0_20px_60px_-42px_rgba(15,35,80,0.48)] sm:p-10">
                    <p className="mb-2 text-sm font-bold text-primary">
                        Curriculum explorer
                    </p>
                    <h1 className="text-3xl font-black tracking-[-0.035em] sm:text-4xl">
                        Browse what every class learns
                    </h1>
                    <p className="mt-3 text-muted-foreground">
                        Subjects, topics and skills for Class 1 to Class 3.
                    </p>
                </header>

                <div className="grid gap-6 lg:grid-cols-3">
                    {classes.map((schoolClass) => (
                        <section
                            key={schoolClass.id}
                            className="surface-card space-y-5 p-6"
                        >
                            <h2 className="text-xl font-black text-primary">
                                <Link href={classHref(schoolClass.slug)}>
                                    {schoolClass.name}
                                </Link>
                            </h2>

                            {schoolClass.subjects.map((subject) => (
                                <div
                                    key={subject.id}
                                    className="space-y-3 border-t border-border/70 pt-4"
                                >
                                    <h3 className="font-extrabold">
                                        <Link
                                            href={subjectHref(
                                                schoolClass.slug,
                                                subject.slug,
                                            )}
                                        >
                                            {subject.name}
                                        </Link>
                                    </h3>

                                    {subject.topics.map((topic) => (
                                        <div
                                            key={topic.id}
                                            className="space-y-2 rounded-xl bg-muted/35 p-3"
                                        >
                                            <h4 className="text-sm font-bold text-foreground/70">
                                                <Link
                                                    href={topicHref(
                                                        schoolClass.slug,
                                                        subject.slug,
                                                        topic.slug,
                                                    )}
                                                >
                                                    {topic.name}
                                                </Link>
                                            </h4>
                                            <ul className="list-disc space-y-1 pl-5 text-xs text-muted-foreground">
                                                {topic.skills.map((skill) => (
                                                    <li key={skill.id}>
                                                        {skill.name}
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>
                                    ))}
                                </div>
                            ))}
                        </section>
                    ))}
                </div>
            </div>
        </PublicLayout>
    );
}
