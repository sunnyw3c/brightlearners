import { Link } from '@inertiajs/react';
import { BookOpen, Sparkles, Star } from 'lucide-react';
import { classHref } from '@/lib/public-urls';
import type { SchoolClassSummary } from '@/types/public';

const PALETTE = [
    { tint: 'bg-warm/10', icon: Star, iconTint: 'text-warm' },
    { tint: 'bg-success/10', icon: BookOpen, iconTint: 'text-success' },
    { tint: 'bg-primary/10', icon: Sparkles, iconTint: 'text-primary' },
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
    const { tint, icon: ClassIcon, iconTint } = PALETTE[index % PALETTE.length];

    return (
        <Link href={classHref(schoolClass.slug)}>
            <div
                className={`flex h-full flex-col gap-3 rounded-2xl border p-6 shadow-sm transition-shadow hover:shadow-md ${tint}`}
            >
                <span
                    className={`flex size-10 items-center justify-center rounded-xl bg-card ${iconTint}`}
                >
                    <ClassIcon className="size-5" />
                </span>
                <div>
                    <p className="text-lg font-semibold">{schoolClass.name}</p>
                    <p className="text-sm text-muted-foreground">
                        {tagline ?? 'Explore with confidence'}
                    </p>
                </div>
            </div>
        </Link>
    );
}
