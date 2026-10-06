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
        <div className="space-y-3">
            <div
                className="overflow-hidden rounded-lg border bg-muted"
                style={{ aspectRatio: `${active.width} / ${active.height}` }}
            >
                <img
                    src={active.url}
                    width={active.width}
                    height={active.height}
                    alt={`${title} — page ${activeIndex + 1}`}
                    className="h-full w-full object-contain"
                />
            </div>

            {previews.length > 1 && (
                <div className="flex gap-2">
                    {previews.map((preview, index) => (
                        <button
                            key={preview.url}
                            type="button"
                            onClick={() => setActiveIndex(index)}
                            aria-label={`Show page ${index + 1}`}
                            className={cn(
                                'h-16 w-12 overflow-hidden rounded border',
                                index === activeIndex
                                    ? 'border-primary'
                                    : 'border-border',
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
