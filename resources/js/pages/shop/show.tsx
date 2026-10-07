import { Form, Head } from '@inertiajs/react';
import {
    BookOpenCheck,
    Check,
    Download,
    FileCheck,
    ShieldCheck,
    Sparkles,
} from 'lucide-react';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { MetaList } from '@/components/public/meta-list';
import { PreviewCarousel } from '@/components/public/preview-carousel';
import { ProductCard } from '@/components/public/product-card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { store as addCartItem } from '@/routes/cart/items';
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

            <div className="page-container space-y-12 py-6 sm:py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                <div className="grid grid-cols-1 items-start gap-9 lg:grid-cols-[.9fr_1.1fr] lg:gap-12">
                    {productDetail.cover_image_url ? (
                        <div className="flex min-h-[32rem] items-center justify-center rounded-2xl bg-linear-to-br from-amber-50 via-white to-sky-50 p-8 shadow-[0_20px_55px_-38px_rgba(15,35,80,0.48)]">
                            <img
                                src={productDetail.cover_image_url}
                                width={800}
                                height={1000}
                                alt={`${product.name} cover`}
                                className="max-h-[31rem] w-auto rounded-xl object-contain drop-shadow-xl"
                            />
                        </div>
                    ) : (
                        <PreviewCarousel
                            previews={productDetail.previews}
                            title={product.name}
                        />
                    )}

                    <div className="surface-card space-y-5 p-6 sm:p-8">
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

                        <h1 className="text-3xl font-black tracking-[-0.035em] sm:text-4xl">
                            {product.name}
                        </h1>

                        {product.short_description && (
                            <p className="leading-7 text-muted-foreground">
                                {product.short_description}
                            </p>
                        )}

                        <div className="flex items-baseline gap-3">
                            <span className="text-3xl font-black text-warm">
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
                            <div className="rounded-2xl border border-primary/10 bg-primary/5 p-5">
                                <p className="flex items-center gap-2 text-sm font-bold">
                                    <BookOpenCheck className="size-4 text-primary" />
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

                        <Form {...addCartItem.form()}>
                            {({ errors, processing }) => (
                                <>
                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value={product.id}
                                    />
                                    <Button
                                        type="submit"
                                        size="lg"
                                        className="w-full"
                                        disabled={processing}
                                    >
                                        {processing ? 'Adding…' : 'Add to Cart'}
                                    </Button>
                                    {errors.product_id && (
                                        <p className="mt-2 text-sm font-medium text-destructive">
                                            {errors.product_id}
                                        </p>
                                    )}
                                </>
                            )}
                        </Form>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    {trustRow.map(({ icon: TrustIcon, label }) => (
                        <div
                            key={label}
                            className="flex items-center justify-center gap-3 rounded-2xl border border-border/70 bg-white p-4 text-sm font-bold shadow-sm"
                        >
                            <span className="flex size-10 items-center justify-center rounded-xl bg-primary/9 text-primary">
                                <TrustIcon className="size-4" />
                            </span>
                            {label}
                        </div>
                    ))}
                </div>

                {productDetail.cover_image_url &&
                    productDetail.previews.length > 0 && (
                        <section className="space-y-5 rounded-2xl border border-border/70 bg-white p-5 sm:p-7">
                            <div>
                                <p className="mb-1 text-sm font-bold text-primary">
                                    Look inside before you buy
                                </p>
                                <h2 className="section-title">Sample Pages</h2>
                            </div>
                            <div className="max-w-2xl">
                                <PreviewCarousel
                                    previews={productDetail.previews}
                                    title={product.name}
                                />
                            </div>
                        </section>
                    )}

                {related.length > 0 && (
                    <section className="space-y-5 pt-4">
                        <div>
                            <p className="mb-1 flex items-center gap-1.5 text-sm font-bold text-primary">
                                <Sparkles className="size-4" /> Build the next
                                skill
                            </p>
                            <h2 className="section-title">You May Also Like</h2>
                        </div>
                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
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
