import { Head } from '@inertiajs/react';
import {
    BookCheck,
    CheckCircle2,
    Download,
    FileText,
    Printer,
    Sparkles,
} from 'lucide-react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { MetaList } from '@/components/public/meta-list';
import { PreviewCarousel } from '@/components/public/preview-carousel';
import { ResourceCard } from '@/components/public/resource-card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import type {
    BreadcrumbData,
    ResourceCardData,
    SeoProps,
} from '@/types/public';

type ResourceDetail = {
    learning_objective: string | null;
    estimated_minutes: number | null;
    page_count: number | null;
    supplies: string | null;
    has_answer_key: boolean;
    low_ink_available: boolean;
    difficulty: string | null;
    previews: { url: string; width: number; height: number }[];
    download_url: string;
};

type Props = {
    resource: ResourceCardData;
    resourceDetail: ResourceDetail;
    related: ResourceCardData[];
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

export default function FreeResourceShow({
    resource,
    resourceDetail,
    related,
    breadcrumbs,
    seo,
}: Props) {
    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
            </Head>

            <div className="page-container space-y-8 py-6 sm:py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <div className="grid grid-cols-1 items-start gap-8 lg:grid-cols-[1.05fr_.95fr] lg:gap-12">
                    <div className="space-y-4">
                        <div className="inline-flex items-center gap-2 rounded-xl bg-white px-3 py-2 text-xs font-bold text-primary shadow-sm">
                            <FileText className="size-4" /> Printable preview
                        </div>
                        <PreviewCarousel
                            previews={resourceDetail.previews}
                            title={resource.title}
                        />
                    </div>

                    <div className="surface-card space-y-5 p-6 sm:p-8">
                        <div className="flex flex-wrap items-center gap-2">
                            {resource.free && (
                                <Badge className="border-transparent bg-success px-3 py-1 text-success-foreground">
                                    Free Resource
                                </Badge>
                            )}
                            <span className="text-xs font-bold tracking-wide text-primary uppercase">
                                {resource.type}
                            </span>
                        </div>

                        <h1 className="text-3xl font-black tracking-[-0.035em] sm:text-4xl">
                            {resource.title}
                        </h1>

                        {resource.summary && (
                            <p className="leading-7 text-muted-foreground">
                                {resource.summary}
                            </p>
                        )}

                        <MetaList
                            items={[
                                {
                                    label: 'Class',
                                    value: resource.class_name ?? '',
                                },
                                {
                                    label: 'Subject',
                                    value: resource.subject_name ?? '',
                                },
                                {
                                    label: 'Format',
                                    value: 'PDF, printable',
                                },
                                {
                                    label: 'Pages',
                                    value: resourceDetail.page_count
                                        ? String(resourceDetail.page_count)
                                        : '',
                                },
                                {
                                    label: 'Time',
                                    value: resourceDetail.estimated_minutes
                                        ? `${resourceDetail.estimated_minutes} min`
                                        : '',
                                },
                                {
                                    label: 'Includes',
                                    value: resourceDetail.has_answer_key
                                        ? 'Worksheet + Answer Key'
                                        : 'Worksheet',
                                },
                                {
                                    label: 'Supplies',
                                    value: resourceDetail.supplies ?? '',
                                },
                            ]}
                        />

                        {resourceDetail.learning_objective && (
                            <div className="rounded-2xl border border-primary/10 bg-primary/5 p-5">
                                <p className="flex items-center gap-2 text-sm font-bold">
                                    <BookCheck className="size-4 text-primary" />
                                    What your child will learn
                                </p>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    {resourceDetail.learning_objective}
                                </p>
                            </div>
                        )}

                        <div className="grid gap-3 sm:grid-cols-[1fr_auto]">
                            <Button asChild size="lg" className="w-full">
                                <a href={resourceDetail.download_url}>
                                    <Download className="size-4" />
                                    Download Free PDF
                                </a>
                            </Button>
                            <span className="inline-flex items-center justify-center gap-1.5 rounded-xl border bg-muted/30 px-4 text-xs font-bold text-muted-foreground">
                                <Printer className="size-4" /> Print ready
                            </span>
                        </div>

                        <p className="flex items-center gap-2 text-xs font-medium text-muted-foreground">
                            <CheckCircle2 className="size-4 text-success" />
                            Reviewed learning material with a clear objective
                        </p>
                    </div>
                </div>

                {related.length > 0 && (
                    <section className="space-y-5 pt-8">
                        <div>
                            <p className="mb-1 flex items-center gap-1.5 text-sm font-bold text-primary">
                                <Sparkles className="size-4" /> Keep learning
                            </p>
                            <h2 className="section-title">You May Also Like</h2>
                        </div>
                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            {related.map((item) => (
                                <ResourceCard key={item.id} resource={item} />
                            ))}
                        </div>
                    </section>
                )}
            </div>
        </PublicLayout>
    );
}
