import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import { subjectHref } from '@/lib/public-urls';
import type { SubjectSummary } from '@/types/public';

export function SubjectChips({
    classSlug,
    subjects,
    activeSubjectSlug,
}: {
    classSlug: string;
    subjects: SubjectSummary[];
    activeSubjectSlug?: string;
}) {
    if (subjects.length === 0) {
        return null;
    }

    return (
        <div className="flex flex-wrap gap-2">
            {subjects.map((subject) => {
                const isActive = subject.slug === activeSubjectSlug;

                return (
                    <Link
                        key={subject.id}
                        href={subjectHref(classSlug, subject.slug)}
                        className={cn(
                            'rounded-full border px-3 py-1 text-sm font-medium transition-colors',
                            isActive
                                ? 'border-primary bg-primary text-primary-foreground'
                                : 'border-border bg-background text-foreground/80 hover:bg-accent',
                        )}
                    >
                        {subject.name}
                    </Link>
                );
            })}
        </div>
    );
}
