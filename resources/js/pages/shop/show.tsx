import { Head } from '@inertiajs/react';
import { Check, Download, FileCheck, ShieldCheck } from 'lucide-react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { MetaList } from '@/components/public/meta-list';
import { PreviewCarousel } from '@/components/public/preview-carousel';
import { ProductCard } from '@/components/public/product-card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import type { BreadcrumbData, ProductCardData, SeoProps } from '@/types/public';

type Inclusion = {
    title: string;
    type: string;
    page_count: number | null;
};

type ProductDetail = {
    description: string | null;
    total_pages: number;
    has_answer_key: boolean;
    language: string[];
    licence_type: string[];
    inclusions: Inclusion[];
    member_discount_eligible: boolean;
    cover_image_url: string | null;
    previews: { url: string; width: number; height: number }[];
};

type Props = {
    product: ProductCardData;
    productDetail: ProductDetail;
    related: ProductCardData[];
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

const trustRow = [
    { icon: Download, label: 'Instant Download' },
    { icon: ShieldCheck, label: 'Secure Payment' },
    { icon: FileCheck, label: 'High Quality PDF' },
];

export default function ShopShow({
    product,
    productDetail,
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

            <div className="mx-auto max-w-6xl space-y-10 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <div className="grid grid-cols-1 gap-10 lg:grid-cols-2">
                    <PreviewCarousel
                        previews={productDetail.previews}
                        title={product.name}
                    />

                    <div className="space-y-5">
                        <div className="flex items-center gap-2">
                            {product.on_sale && (
                                <Badge className="border-transparent bg-highlight text-highlight-foreground">
                                    Sale
                                </Badge>
                            )}
                            {product.class_name && (
                                <span className="text-xs font-medium text-muted-foreground">
                                    {product.class_name}
                                </span>
                            )}
                        </div>

                        <h1 className="text-2xl font-bold sm:text-3xl">
                            {product.name}
                        </h1>

                        {product.short_description && (
                            <p className="text-muted-foreground">
                                {product.short_description}
                            </p>
                        )}

                        <div className="flex items-baseline gap-3">
                            <span className="text-3xl font-extrabold text-warm">
                                {product.price?.formatted ??
                                    product.regular_price.formatted}
                            </span>
                            {product.on_sale && (
                                <span className="text-muted-foreground line-through">
                                    {product.regular_price.formatted}
                                </span>
                            )}
                        </div>

                        <MetaList
                            items={[
                                {
                                    label: 'Total pages',
                                    value: String(productDetail.total_pages),
                                },
                                {
                                    label: 'Answer key',
                                    value: productDetail.has_answer_key
                                        ? 'Included'
                                        : '',
                                },
                                {
                                    label: 'Language',
                                    value: productDetail.language.join(', '),
                                },
                                {
                                    label: 'Licence',
                                    value: productDetail.licence_type.join(
                                        ', ',
                                    ),
                                },
                                {
                                    label: 'Delivery',
                                    value: 'Instant PDF download',
                                },
                            ]}
                        />

                        {productDetail.description && (
                            <p className="text-sm text-muted-foreground">
                                {productDetail.description}
                            </p>
                        )}

                        {productDetail.inclusions.length > 0 && (
                            <div className="rounded-2xl border bg-muted/30 p-5">
                                <p className="text-sm font-semibold">
                                    What&apos;s included
                                </p>
                                <ul className="mt-3 space-y-2 text-sm">
                                    {productDetail.inclusions.map(
                                        (inclusion, index) => (
                                            <li
                                                key={index}
                                                className="flex items-start gap-2"
                                            >
                                                <Check className="mt-0.5 size-4 shrink-0 text-success" />
                                                <span>
                                                    {inclusion.title}
                                                    {inclusion.page_count
                                                        ? ` — ${inclusion.page_count} pages`
                                                        : ''}
                                                </span>
                                            </li>
                                        ),
                                    )}
                                </ul>
                            </div>
                        )}

                        <Button
                            size="lg"
                            className="w-full sm:w-auto"
                            disabled
                            title="Coming soon — cart arrives in the next phase"
                        >
                            Add to Cart
                        </Button>

                        <div className="grid grid-cols-3 gap-3 border-t pt-5">
                            {trustRow.map(({ icon: TrustIcon, label }) => (
                                <div
                                    key={label}
                                    className="flex flex-col items-center gap-1.5 text-center text-xs text-muted-foreground"
                                >
                                    <span className="flex size-9 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <TrustIcon className="size-4" />
                                    </span>
                                    {label}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {related.length > 0 && (
                    <section className="space-y-4">
                        <h2 className="text-xl font-semibold">
                            You May Also Like
                        </h2>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {related.map((item) => (
                                <ProductCard key={item.id} product={item} />
                            ))}
                        </div>
                    </section>
                )}
            </div>
        </PublicLayout>
    );
}
