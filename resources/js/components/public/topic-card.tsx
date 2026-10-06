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
        <Link href={topicHref(classSlug, subjectSlug, topic.slug)}>
            <div className="flex h-full flex-col items-center gap-2 rounded-2xl border bg-card p-5 text-center shadow-sm transition-shadow hover:shadow-md">
                <span
                    className={`flex size-11 items-center justify-center rounded-full ${tint} ${iconTint}`}
                >
                    <TopicIcon className="size-5" />
                </span>
                <p className="font-medium">{topic.name}</p>
                {typeof topic.resource_count === 'number' && (
                    <p className="text-xs text-muted-foreground">
                        {topic.resource_count}{' '}
                        {topic.resource_count === 1 ? 'Resource' : 'Resources'}
                    </p>
                )}
            </div>
        </Link>
    );
}
