import { Link } from '@inertiajs/react';
import { FileText } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import type { ResourceCardData } from '@/types/public';

export function ResourceCard({ resource }: { resource: ResourceCardData }) {
    const content = (
        <div className="flex h-full flex-col overflow-hidden rounded-2xl border bg-card shadow-sm transition-shadow hover:shadow-md">
            <div className="aspect-4/3 w-full overflow-hidden bg-linear-to-br from-primary/10 via-highlight/10 to-warm/10">
                {resource.preview_image_url ? (
                    <img
                        src={resource.preview_image_url}
                        width={resource.preview_width ?? undefined}
                        height={resource.preview_height ?? undefined}
                        alt={resource.title}
                        loading="lazy"
                        className="h-full w-full object-cover"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center">
                        <FileText className="size-10 text-primary/50" />
                    </div>
                )}
            </div>
            <div className="flex flex-1 flex-col gap-2 p-4">
                <div className="flex items-center gap-2">
                    {resource.free && (
                        <Badge className="border-transparent bg-success text-success-foreground">
                            FREE
                        </Badge>
                    )}
                    {resource.class_name && (
                        <span className="text-xs text-muted-foreground">
                            {resource.class_name}
                            {resource.subject_name
                                ? ` · ${resource.subject_name}`
                                : ''}
                        </span>
                    )}
                </div>
                <p className="text-base leading-snug font-semibold">
                    {resource.title}
                </p>
                {resource.summary && (
                    <p className="line-clamp-2 text-sm text-muted-foreground">
                        {resource.summary}
                    </p>
                )}
                {(resource.estimated_minutes || resource.page_count) && (
                    <div className="mt-auto flex gap-3 pt-1 text-xs text-muted-foreground">
                        {resource.estimated_minutes && (
                            <span>{resource.estimated_minutes} min</span>
                        )}
                        {resource.page_count && (
                            <span>
                                {resource.page_count}{' '}
                                {resource.page_count === 1 ? 'page' : 'pages'}
                            </span>
                        )}
                    </div>
                )}
            </div>
        </div>
    );

    if (!resource.url) {
        return content;
    }

    return <Link href={resource.url}>{content}</Link>;
}
