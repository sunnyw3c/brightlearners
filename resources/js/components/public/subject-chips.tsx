import { Link } from '@inertiajs/react';
import {
    BookOpen,
    Calculator,
    Languages,
    Leaf,
    LayoutGrid,
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { classHref, subjectHref } from '@/lib/public-urls';
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

    const subjectIcons = {
        maths: Calculator,
        math: Calculator,
        english: Languages,
        evs: Leaf,
    };

    return (
        <div className="flex flex-wrap gap-2 rounded-2xl border border-border/70 bg-white/75 p-2 shadow-sm backdrop-blur">
            <Link
                href={classHref(classSlug)}
                className={cn(
                    'rounded-xl px-4 py-2 text-sm font-bold transition-all',
                    !activeSubjectSlug
                        ? 'bg-primary text-primary-foreground shadow-sm'
                        : 'text-foreground/65 hover:bg-accent',
                )}
            >
                <LayoutGrid className="size-4" />
                All
            </Link>
            {subjects.map((subject) => {
                const isActive = subject.slug === activeSubjectSlug;
                const SubjectIcon =
                    subjectIcons[subject.slug as keyof typeof subjectIcons] ??
                    BookOpen;

                return (
                    <Link
                        key={subject.id}
                        href={subjectHref(classSlug, subject.slug)}
                        className={cn(
                            'rounded-xl px-4 py-2 text-sm font-bold transition-all',
                            isActive
                                ? 'bg-primary text-primary-foreground shadow-sm'
                                : 'text-foreground/65 hover:bg-accent hover:text-foreground',
                        )}
                    >
                        <SubjectIcon className="size-4" />
                        {subject.name}
                    </Link>
                );
            })}
        </div>
    );
}
