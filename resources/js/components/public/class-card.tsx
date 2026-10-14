import { Link } from '@inertiajs/react';
import { BookOpen, Sparkles, Star } from 'lucide-react';
import { classHref } from '@/lib/public-urls';
import type { SchoolClassSummary } from '@/types/public';

type ClassTheme = {
    bg: string;
    border: string;
    badgeBg: string;
    badgeColor: string;
    icon: typeof Star;
    illustration: string;
    defaultTagline: string;
};

const CLASS_THEMES: Record<string, ClassTheme> = {
    'class-1': {
        bg: 'bg-[#FFF3ED] hover:bg-[#FFEAE0]',
        border: 'border-[#FFE2D1]',
        badgeBg: 'bg-white shadow-xs',
        badgeColor: 'text-amber-500',
        icon: Star,
        illustration: '/images/class-1-girl.png',
        defaultTagline: 'Build strong foundations',
    },
    'class-2': {
        bg: 'bg-[#E8FAF4] hover:bg-[#DDF7ED]',
        border: 'border-[#D1F5E8]',
        badgeBg: 'bg-white shadow-xs',
        badgeColor: 'text-teal-600',
        icon: BookOpen,
        illustration: '/images/class-2-boy.png',
        defaultTagline: 'Explore with confidence',
    },
    'class-3': {
        bg: 'bg-[#EEF4FF] hover:bg-[#E3EDFF]',
        border: 'border-[#D9E6FF]',
        badgeBg: 'bg-white shadow-xs',
        badgeColor: 'text-blue-600',
        icon: Sparkles,
        illustration: '/images/class-3-student.png',
        defaultTagline: 'Grow skills for tomorrow',
    },
};

const FALLBACK_THEMES: ClassTheme[] = [
    CLASS_THEMES['class-1'],
    CLASS_THEMES['class-2'],
    CLASS_THEMES['class-3'],
];

export function ClassCard({
    schoolClass,
    index = 0,
    tagline,
}: {
    schoolClass: SchoolClassSummary;
    index?: number;
    tagline?: string;
}) {
    const theme =
        CLASS_THEMES[schoolClass.slug] ??
        FALLBACK_THEMES[index % FALLBACK_THEMES.length];
    const { icon: ClassIcon } = theme;

    return (
        <Link
            href={classHref(schoolClass.slug)}
            className="group block h-full focus:outline-hidden"
        >
            <div
                className={`relative flex h-full items-center justify-between overflow-hidden rounded-3xl border p-6 transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-lg ${theme.bg} ${theme.border}`}
            >
                <div className="z-10 flex flex-col justify-between space-y-3">
                    <span
                        className={`flex size-11 items-center justify-center rounded-2xl ${theme.badgeBg} ${theme.badgeColor}`}
                    >
                        <ClassIcon className="size-5 fill-current" />
                    </span>
                    <div>
                        <p className="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                            {schoolClass.name}
                        </p>
                        <p className="mt-0.5 text-sm font-medium text-slate-600 dark:text-slate-300">
                            {tagline ?? theme.defaultTagline}
                        </p>
                    </div>
                </div>

                <div className="relative -mr-2 -mb-2 h-28 w-28 shrink-0 transition-transform duration-300 group-hover:scale-105 sm:h-32 sm:w-32">
                    <img
                        src={theme.illustration}
                        alt={`${schoolClass.name} student`}
                        loading="lazy"
                        className="h-full w-full object-contain drop-shadow-sm"
                    />
                </div>
            </div>
        </Link>
    );
}
