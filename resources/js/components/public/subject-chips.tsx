import { Link } from '@inertiajs/react';
import { BookOpen, Calculator, Globe, Leaf, Sparkles } from 'lucide-react';
import { cn } from '@/lib/utils';
import { classHref, subjectHref } from '@/lib/public-urls';
import type { SubjectSummary } from '@/types/public';

function getSubjectIcon(name: string) {
    const lower = name.toLowerCase();
    if (lower.includes('math')) return Calculator;
    if (lower.includes('english')) return BookOpen;
    if (
        lower.includes('evs') ||
        lower.includes('science') ||
        lower.includes('environ')
    )
        return Leaf;
    if (lower.includes('hindi')) return Globe;
    return Sparkles;
}

export function SubjectChips({
    classSlug,
    subjects,
    activeSubjectSlug,
}: {
    classSlug: string;
    subjects: SubjectSummary[];
    activeSubjectSlug?: string;
}) {
    const isAllActive = !activeSubjectSlug;

    return (
        <div className="flex flex-wrap items-center gap-2.5">
            {/* "All" chip */}
            <Link
                href={classHref(classSlug)}
                className={cn(
                    'inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-semibold shadow-xs transition-all',
                    isAllActive
                        ? 'bg-primary text-white shadow-primary/25'
                        : 'border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200',
                )}
            >
                All
            </Link>

            {subjects.map((subject) => {
                const isActive = subject.slug === activeSubjectSlug;
                const IconComponent = getSubjectIcon(subject.name);

                return (
                    <Link
                        key={subject.id}
                        href={subjectHref(classSlug, subject.slug)}
                        className={cn(
                            'inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold shadow-xs transition-all',
                            isActive
                                ? 'bg-primary text-white shadow-primary/25'
                                : 'border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200',
                        )}
                    >
                        <IconComponent className="size-4" />
                        {subject.name}
                    </Link>
                );
            })}
        </div>
    );
}
