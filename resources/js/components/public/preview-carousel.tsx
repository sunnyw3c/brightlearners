import { useState } from 'react';
import { cn } from '@/lib/utils';

type Preview = {
    url: string;
    width: number;
    height: number;
};

export function PreviewCarousel({
    previews,
    title,
}: {
    previews: Preview[];
    title: string;
}) {
    const [activeIndex, setActiveIndex] = useState(0);

    if (previews.length === 0) {
        return null;
    }

    const active = previews[activeIndex];

    return (
        <div className="space-y-4">
            <div
                className="overflow-hidden rounded-3xl border border-white/80 bg-linear-to-br from-sky-50 via-white to-amber-50 p-4 shadow-[0_24px_65px_-38px_rgba(15,35,80,0.5)] sm:p-6"
                style={{ aspectRatio: `${active.width} / ${active.height}` }}
            >
                <img
                    src={active.url}
                    width={active.width}
                    height={active.height}
                    alt={`${title} — page ${activeIndex + 1}`}
                    className="h-full w-full rounded-xl object-contain shadow-sm"
                />
            </div>

            {previews.length > 1 && (
                <div className="flex gap-2 overflow-x-auto pb-1">
                    {previews.map((preview, index) => (
                        <button
                            key={preview.url}
                            type="button"
                            onClick={() => setActiveIndex(index)}
                            aria-label={`Show page ${index + 1}`}
                            className={cn(
                                'h-20 w-14 shrink-0 overflow-hidden rounded-xl border-2 bg-white p-1 shadow-sm transition-all',
                                index === activeIndex
                                    ? 'border-primary ring-2 ring-primary/10'
                                    : 'border-border hover:border-primary/35',
                            )}
                        >
                            <img
                                src={preview.url}
                                width={preview.width}
                                height={preview.height}
                                alt=""
                                className="h-full w-full object-cover"
                            />
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
}
