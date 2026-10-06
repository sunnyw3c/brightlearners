import { Head } from '@inertiajs/react';
import { Download } from 'lucide-react';
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

            <div className="mx-auto max-w-6xl space-y-8 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <div className="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <PreviewCarousel
                        previews={resourceDetail.previews}
                        title={resource.title}
                    />

                    <div className="space-y-4">
                        <div className="flex items-center gap-2">
                            {resource.free && (
                                <Badge className="border-transparent bg-success text-success-foreground">
                                    Free Resource
                                </Badge>
                            )}
                        </div>

                        <h1 className="text-2xl font-bold sm:text-3xl">
                            {resource.title}
                        </h1>

                        {resource.summary && (
                            <p className="text-muted-foreground">
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
                            <div className="rounded-2xl border bg-muted/30 p-5">
                                <p className="text-sm font-semibold">
                                    What your child will learn
                                </p>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    {resourceDetail.learning_objective}
                                </p>
                            </div>
                        )}

                        <Button asChild size="lg">
                            <a href={resourceDetail.download_url}>
                                <Download className="mr-1 size-4" />
                                Download Free PDF
                            </a>
                        </Button>
                    </div>
                </div>

                {related.length > 0 && (
                    <section className="space-y-4">
                        <h2 className="text-xl font-semibold">
                            You May Also Like
                        </h2>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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
