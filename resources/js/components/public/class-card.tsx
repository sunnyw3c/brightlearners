import { Link } from '@inertiajs/react';
import { ArrowRight, BookOpen, Sparkles, Star } from 'lucide-react';
import { classHref } from '@/lib/public-urls';
import type { SchoolClassSummary } from '@/types/public';

const PALETTE = [
    {
        tint: 'from-rose-50 to-orange-50',
        orb: 'bg-rose-100 text-rose-500',
        icon: Star,
        blob: 'bg-rose-200/45',
    },
    {
        tint: 'from-emerald-50 to-cyan-50',
        orb: 'bg-emerald-100 text-emerald-600',
        icon: BookOpen,
        blob: 'bg-emerald-200/45',
    },
    {
        tint: 'from-sky-50 to-indigo-50',
        orb: 'bg-sky-100 text-primary',
        icon: Sparkles,
        blob: 'bg-sky-200/55',
    },
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
    const {
        tint,
        orb,
        icon: ClassIcon,
        blob,
    } = PALETTE[index % PALETTE.length];

    return (
        <Link href={classHref(schoolClass.slug)} className="group">
            <div
                className={`relative flex h-full min-h-44 items-end overflow-hidden rounded-3xl border border-white/80 bg-linear-to-br p-6 shadow-[0_18px_45px_-30px_rgba(15,35,80,0.38)] transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-[0_24px_55px_-28px_rgba(15,35,80,0.42)] ${tint}`}
            >
                <span
                    className={`absolute -top-9 -right-7 size-32 rounded-full ${blob}`}
                />
                <span
                    className={`absolute top-5 right-5 flex size-16 rotate-6 items-center justify-center rounded-[1.4rem] bg-white/85 shadow-sm ${orb}`}
                >
                    <ClassIcon className="size-8" strokeWidth={1.8} />
                </span>
                <div className="relative">
                    <p className="text-xl font-black tracking-tight">
                        {schoolClass.name}
                    </p>
                    <p className="mt-1.5 text-sm text-foreground/55">
                        {tagline ?? 'Explore with confidence'}
                    </p>
                    <span className="mt-4 inline-flex items-center gap-1 text-xs font-bold text-primary transition-all group-hover:gap-2">
                        Start learning <ArrowRight className="size-3.5" />
                    </span>
                </div>
            </div>
        </Link>
    );
}
