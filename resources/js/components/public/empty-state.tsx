import { SearchX } from 'lucide-react';
import type { ReactNode } from 'react';

export function EmptyState({
    title,
    description,
    action,
}: {
    title: string;
    description?: string;
    action?: ReactNode;
}) {
    return (
        <div className="flex flex-col items-center gap-3 rounded-3xl border border-dashed border-primary/20 bg-white/65 p-12 text-center shadow-sm">
            <span className="flex size-14 items-center justify-center rounded-2xl bg-primary/8 text-primary">
                <SearchX className="size-6" />
            </span>
            <p className="text-lg font-extrabold">{title}</p>
            {description && (
                <p className="max-w-md text-sm text-muted-foreground">
                    {description}
                </p>
            )}
            {action}
        </div>
    );
}
