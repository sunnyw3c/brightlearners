import { Link } from '@inertiajs/react';
import { ArrowRight, Clock3, FileText, Files } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import type { ResourceCardData } from '@/types/public';

export function ResourceCard({ resource }: { resource: ResourceCardData }) {
    const content = (
        <article className="group flex h-full flex-col overflow-hidden rounded-3xl border border-border/70 bg-card shadow-[0_16px_45px_-32px_rgba(15,35,80,0.42)] transition-all duration-300 hover:-translate-y-1 hover:border-primary/20 hover:shadow-[0_24px_60px_-32px_rgba(15,35,80,0.45)]">
            <div className="relative aspect-4/3 w-full overflow-hidden bg-linear-to-br from-sky-50 via-amber-50 to-rose-50 p-3">
                {resource.free && (
                    <Badge className="absolute top-4 left-4 z-10 border-2 border-white bg-success px-2.5 py-1 text-[0.65rem] font-black tracking-wider text-success-foreground shadow-sm">
                        FREE
                    </Badge>
                )}
                {resource.preview_image_url ? (
                    <img
                        src={resource.preview_image_url}
                        width={resource.preview_width ?? undefined}
                        height={resource.preview_height ?? undefined}
                        alt={resource.title}
                        loading="lazy"
                        className="h-full w-full rounded-2xl object-cover shadow-sm transition-transform duration-500 group-hover:scale-[1.03]"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center rounded-2xl border border-white/80 bg-white/75">
                        <FileText
                            className="size-12 text-primary/45"
                            strokeWidth={1.5}
                        />
                    </div>
                )}
            </div>
            <div className="flex flex-1 flex-col gap-3 p-5">
                {(resource.class_name || resource.subject_name) && (
                    <p className="text-[0.68rem] font-bold tracking-[0.08em] text-primary/75 uppercase">
                        {[resource.class_name, resource.subject_name]
                            .filter(Boolean)
                            .join(' · ')}
                    </p>
                )}
                <h3 className="text-base leading-snug font-extrabold tracking-tight">
                    {resource.title}
                </h3>
                {resource.summary && (
                    <p className="line-clamp-2 text-sm leading-5 text-muted-foreground">
                        {resource.summary}
                    </p>
                )}
                <div className="mt-auto flex items-center justify-between gap-3 border-t border-border/70 pt-4">
                    <div className="flex gap-3 text-xs font-medium text-muted-foreground">
                        {resource.estimated_minutes && (
                            <span className="inline-flex items-center gap-1">
                                <Clock3 className="size-3.5" />{' '}
                                {resource.estimated_minutes} min
                            </span>
                        )}
                        {resource.page_count && (
                            <span className="inline-flex items-center gap-1">
                                <Files className="size-3.5" />{' '}
                                {resource.page_count}{' '}
                                {resource.page_count === 1 ? 'page' : 'pages'}
                            </span>
                        )}
                    </div>
                    <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-primary/9 text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground">
                        <ArrowRight className="size-4" />
                    </span>
                </div>
            </div>
        </article>
    );

    return resource.url ? <Link href={resource.url}>{content}</Link> : content;
}
