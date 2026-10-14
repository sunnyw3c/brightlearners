import { useState } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';

type Preview = {
    url: string;
    width: number;
    height: number;
};

export function PreviewCarousel({
    previews = [],
    title,
    fallbackUrl = '/images/worksheet-addition.jpg',
}: {
    previews?: Preview[];
    title: string;
    fallbackUrl?: string;
}) {
    const fallbackList = [
        fallbackUrl && !fallbackUrl.includes('/storage/previews/')
            ? fallbackUrl
            : '/images/worksheet-addition.jpg',
        '/images/worksheet-place-value.jpg',
        '/images/worksheet-time-calendar.jpg',
        '/images/worksheet-describing-words.jpg',
    ];

    const initialList = previews && previews.length > 0 ? [...previews] : [];
    while (initialList.length < 4) {
        initialList.push({
            url: fallbackList[initialList.length % fallbackList.length],
            width: 800,
            height: 1100,
        });
    }
    const list = initialList;

    const [activeIndex, setActiveIndex] = useState(0);
    const [brokenUrls, setBrokenUrls] = useState<Record<string, boolean>>({});

    const getSafeUrl = (url: string, index: number) => {
        if (!url || brokenUrls[url] || url.includes('/storage/previews/')) {
            return fallbackList[index % fallbackList.length];
        }
        return url;
    };

    const active = list[activeIndex] || list[0];
    const activeUrl = getSafeUrl(active.url, activeIndex);

    const handlePrev = () => {
        setActiveIndex((prev) => (prev > 0 ? prev - 1 : list.length - 1));
    };

    const handleNext = () => {
        setActiveIndex((prev) => (prev < list.length - 1 ? prev + 1 : 0));
    };

    return (
        <div className="space-y-4">
            {/* Main Preview Container */}
            <div className="group relative overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-3 shadow-md dark:border-slate-800 dark:bg-slate-900">
                <div className="relative flex aspect-3/4 w-full items-center justify-center overflow-hidden rounded-2xl bg-slate-50 dark:bg-slate-950">
                    <img
                        src={activeUrl}
                        onError={() =>
                            setBrokenUrls((prev) => ({
                                ...prev,
                                [active.url]: true,
                            }))
                        }
                        alt={`${title} — page ${activeIndex + 1}`}
                        className="h-full w-full object-contain transition-transform duration-300 group-hover:scale-[1.02]"
                    />
                </div>
            </div>

            {/* Thumbnail Navigation Row: < [1] [2] [3] [4] > */}
            <div className="flex items-center justify-center gap-2">
                <button
                    type="button"
                    onClick={handlePrev}
                    aria-label="Previous preview"
                    className="flex size-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-slate-50 hover:text-primary dark:border-slate-800 dark:bg-slate-900"
                >
                    <ChevronLeft className="size-4" />
                </button>

                <div className="flex items-center gap-2">
                    {list.map((item, index) => (
                        <button
                            key={index}
                            type="button"
                            onClick={() => setActiveIndex(index)}
                            aria-label={`Show page ${index + 1}`}
                            className={cn(
                                'h-14 w-11 overflow-hidden rounded-xl border-2 transition-all',
                                index === activeIndex
                                    ? 'scale-105 border-primary shadow-sm ring-2 ring-primary/20'
                                    : 'border-slate-200 opacity-70 hover:border-slate-300 hover:opacity-100 dark:border-slate-800',
                            )}
                        >
                            <img
                                src={getSafeUrl(item.url, index)}
                                onError={() =>
                                    setBrokenUrls((prev) => ({
                                        ...prev,
                                        [item.url]: true,
                                    }))
                                }
                                alt={`Thumbnail ${index + 1}`}
                                className="h-full w-full object-cover"
                            />
                        </button>
                    ))}
                </div>

                <button
                    type="button"
                    onClick={handleNext}
                    aria-label="Next preview"
                    className="flex size-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-xs hover:bg-slate-50 hover:text-primary dark:border-slate-800 dark:bg-slate-900"
                >
                    <ChevronRight className="size-4" />
                </button>
            </div>
        </div>
    );
}
