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

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <header className="space-y-1">
                    <h1 className="text-2xl font-semibold">
                        Browse what every class learns
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Subjects, topics and skills for Class 1 to Class 3.
                    </p>
                </header>

                {classes.map((schoolClass) => (
                    <section key={schoolClass.id} className="space-y-4">
                        <h2 className="text-lg font-semibold">
                            <Link href={classHref(schoolClass.slug)}>
                                {schoolClass.name}
                            </Link>
                        </h2>

                        {schoolClass.subjects.map((subject) => (
                            <div key={subject.id} className="space-y-2 pl-4">
                                <h3 className="font-medium">
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
                                        className="space-y-1 pl-4"
                                    >
                                        <h4 className="text-sm font-medium text-muted-foreground">
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
                                        <ul className="list-disc space-y-1 pl-6 text-sm">
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
        </PublicLayout>
    );
}
