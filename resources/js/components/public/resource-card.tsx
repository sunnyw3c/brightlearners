import { useState } from 'react';
import { Link } from '@inertiajs/react';
import { Download, Heart, Star } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import type { ResourceCardData } from '@/types/public';

function getFallbackImage(resource: ResourceCardData): string {
    const text = (
        (resource.title || '') +
        ' ' +
        (resource.slug || '') +
        ' ' +
        (resource.subject_name || '')
    ).toLowerCase();

    if (
        text.includes('place') ||
        text.includes('value') ||
        text.includes('number') ||
        text.includes('1000')
    ) {
        return '/images/worksheet-place-value.jpg';
    }
    if (
        text.includes('time') ||
        text.includes('calendar') ||
        text.includes('clock') ||
        text.includes('plant')
    ) {
        return '/images/worksheet-time-calendar.jpg';
    }
    if (
        text.includes('describing') ||
        text.includes('word') ||
        text.includes('english') ||
        text.includes('grammar') ||
        text.includes('reading')
    ) {
        return '/images/worksheet-describing-words.jpg';
    }
    if (text.includes('addition') || text.includes('subtraction')) {
        return '/images/worksheet-addition.jpg';
    }

    const fallbacks = [
        '/images/worksheet-addition.jpg',
        '/images/worksheet-place-value.jpg',
        '/images/worksheet-time-calendar.jpg',
        '/images/worksheet-describing-words.jpg',
    ];
    const id = resource.id || 0;
    return fallbacks[id % fallbacks.length];
}

export function ResourceCard({
    resource,
    showDownloadButton = true,
}: {
    resource: ResourceCardData;
    showDownloadButton?: boolean;
}) {
    const [isSaved, setIsSaved] = useState(false);
    const resolvedUrl =
        resource.preview_image_url &&
        !resource.preview_image_url.includes('/storage/previews/')
            ? resource.preview_image_url
            : getFallbackImage(resource);

    const [imgSrc, setImgSrc] = useState<string>(resolvedUrl);

    const handleImageError = () => {
        const fallback = getFallbackImage(resource);
        if (imgSrc !== fallback) {
            setImgSrc(fallback);
        }
    };

    const cardContent = (
        <div className="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200/80 bg-card p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-800">
            {/* Top Preview Frame */}
            <div className="relative aspect-4/3 w-full overflow-hidden rounded-2xl border border-slate-100 bg-slate-50 dark:border-slate-800/80 dark:bg-slate-900">
                <img
                    src={imgSrc}
                    onError={handleImageError}
                    width={resource.preview_width ?? undefined}
                    height={resource.preview_height ?? undefined}
                    alt={resource.title}
                    loading="lazy"
                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                />

                {/* Free Badge */}
                {resource.free && (
                    <div className="absolute top-3 left-3">
                        <span className="inline-flex items-center rounded-full bg-emerald-500 px-2.5 py-0.5 text-xs font-bold text-white shadow-xs">
                            FREE
                        </span>
                    </div>
                )}

                {/* Heart / Favourite button */}
                <button
                    type="button"
                    onClick={(e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        setIsSaved(!isSaved);
                    }}
                    aria-label="Save resource"
                    className={`absolute top-3 right-3 flex size-8 items-center justify-center rounded-full bg-white/90 shadow-sm backdrop-blur-xs transition-colors hover:bg-white ${
                        isSaved
                            ? 'text-rose-500'
                            : 'text-slate-500 hover:text-rose-500'
                    }`}
                >
                    <Heart
                        className={`size-4 ${isSaved ? 'fill-rose-500 text-rose-500' : ''}`}
                    />
                </button>
            </div>

            {/* Details Section */}
            <div className="flex flex-1 flex-col pt-3.5">
                <div className="mb-1 flex items-center justify-between text-xs text-muted-foreground">
                    <span>
                        {resource.class_name ?? 'Class 2'}
                        {resource.subject_name
                            ? ` · ${resource.subject_name}`
                            : ' · Maths'}
                    </span>
                    <div className="flex items-center gap-1 font-semibold text-amber-500">
                        <Star className="size-3.5 fill-amber-400 stroke-amber-500" />
                        <span>4.8</span>
                    </div>
                </div>

                <h3 className="line-clamp-2 text-base font-bold text-foreground transition-colors group-hover:text-primary">
                    {resource.title}
                </h3>

                {resource.summary && (
                    <p className="mt-1 line-clamp-2 text-xs text-muted-foreground">
                        {resource.summary}
                    </p>
                )}

                {/* Footer Action */}
                <div className="mt-auto flex items-center justify-between gap-2 border-t border-slate-100 pt-4 dark:border-slate-800">
                    <span className="text-xs font-medium text-slate-500">
                        {resource.page_count
                            ? `${resource.page_count} Pages`
                            : '4 Pages'}
                    </span>

                    {showDownloadButton && (
                        <span className="inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5 text-xs font-semibold text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                            <Download className="size-3.5" />
                            Download
                        </span>
                    )}
                </div>
            </div>
        </div>
    );

    if (!resource.url) {
        return cardContent;
    }

    return (
        <Link href={resource.url} className="block h-full focus:outline-hidden">
            {cardContent}
        </Link>
    );
}
