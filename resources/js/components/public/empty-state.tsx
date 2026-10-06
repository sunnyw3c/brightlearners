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
        <div className="flex flex-col items-center gap-3 rounded-2xl border border-dashed bg-muted/20 p-12 text-center">
            <span className="flex size-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
                <SearchX className="size-5" />
            </span>
            <p className="text-base font-semibold">{title}</p>
            {description && (
                <p className="max-w-md text-sm text-muted-foreground">
                    {description}
                </p>
            )}
            {action}
        </div>
    );
}
