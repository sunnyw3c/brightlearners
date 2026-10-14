import { Link } from '@inertiajs/react';
import { Compass, Hash, Plus, Ruler, Shapes, Sparkles, X } from 'lucide-react';
import { topicHref } from '@/lib/public-urls';

const PALETTE = [
    {
        tint: 'bg-cyan-50 text-cyan-600 border-cyan-100 dark:bg-cyan-950/40 dark:text-cyan-400',
        icon: Hash,
    },
    {
        tint: 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400',
        icon: Plus,
    },
    {
        tint: 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400',
        icon: X,
    },
    {
        tint: 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/40 dark:text-purple-400',
        icon: Shapes,
    },
    {
        tint: 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-950/40 dark:text-blue-400',
        icon: Ruler,
    },
];

export function TopicCard({
    classSlug,
    subjectSlug,
    topic,
    index = 0,
}: {
    classSlug: string;
    subjectSlug: string;
    topic: { id: number; name: string; slug: string; resource_count?: number };
    index?: number;
}) {
    const item = PALETTE[index % PALETTE.length];
    const TopicIcon = item.icon;

    return (
        <Link
            href={topicHref(classSlug, subjectSlug, topic.slug)}
            className="group block h-full focus:outline-hidden"
        >
            <div className="flex h-full flex-col items-center justify-center gap-2.5 rounded-3xl border border-slate-200/80 bg-card p-5 text-center shadow-xs transition-all duration-300 group-hover:-translate-y-1 group-hover:border-primary/30 group-hover:shadow-md dark:border-slate-800">
                <span
                    className={`flex size-14 items-center justify-center rounded-full border shadow-xs transition-transform duration-300 group-hover:scale-110 ${item.tint}`}
                >
                    <TopicIcon className="size-6 stroke-[2.2]" />
                </span>
                <div>
                    <p className="text-sm font-bold text-foreground transition-colors group-hover:text-primary">
                        {topic.name}
                    </p>
                    {typeof topic.resource_count === 'number' && (
                        <p className="mt-0.5 text-xs font-medium text-muted-foreground">
                            {topic.resource_count}{' '}
                            {topic.resource_count === 1
                                ? 'Resource'
                                : 'Resources'}
                        </p>
                    )}
                </div>
            </div>
        </Link>
    );
}
