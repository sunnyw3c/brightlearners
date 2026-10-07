import { Link } from '@inertiajs/react';
import { Hash, Plus, Ruler, Shapes, X } from 'lucide-react';
import { topicHref } from '@/lib/public-urls';

const PALETTE = [
    { tint: 'bg-primary/10', iconTint: 'text-primary', icon: Hash },
    { tint: 'bg-warm/10', iconTint: 'text-warm', icon: Plus },
    { tint: 'bg-success/10', iconTint: 'text-success', icon: X },
    { tint: 'bg-violet-500/10', iconTint: 'text-violet-600', icon: Shapes },
    { tint: 'bg-orange-500/10', iconTint: 'text-orange-600', icon: Ruler },
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
    const { tint, iconTint, icon: TopicIcon } = PALETTE[index % PALETTE.length];

    return (
        <Link
            href={topicHref(classSlug, subjectSlug, topic.slug)}
            className="group"
        >
            <div className="flex h-full min-h-40 flex-col items-start gap-3 rounded-3xl border border-border/70 bg-card p-5 text-left shadow-[0_14px_38px_-30px_rgba(15,35,80,0.42)] transition-all duration-300 group-hover:-translate-y-1 group-hover:border-primary/20 group-hover:shadow-lg">
                <span
                    className={`flex size-12 items-center justify-center rounded-2xl transition-transform duration-300 group-hover:scale-105 group-hover:rotate-3 ${tint} ${iconTint}`}
                >
                    <TopicIcon className="size-6" />
                </span>
                <p className="mt-auto leading-snug font-extrabold">
                    {topic.name}
                </p>
                {typeof topic.resource_count === 'number' && (
                    <p className="text-xs font-medium text-muted-foreground">
                        {topic.resource_count}{' '}
                        {topic.resource_count === 1 ? 'Resource' : 'Resources'}
                    </p>
                )}
            </div>
        </Link>
    );
}
