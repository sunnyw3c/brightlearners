import { Head } from '@inertiajs/react';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { ProductCard } from '@/components/public/product-card';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { type as shopTypeRoute } from '@/routes/shop';
import type { Paginated, ProductCardData, SeoProps } from '@/types/public';

type FilterOption = { id: number; name: string; slug: string };

type Props = {
    type: string;
    typeSegment: string;
    products: Paginated<ProductCardData>;
    filters: {
        class: string | null;
        subject: string | null;
        price_band: string | null;
        q: string | null;
    };
    filterOptions: {
        classes: FilterOption[];
        subjects: FilterOption[];
        price_bands: string[];
    };
    seo: SeoProps;
};

const PRICE_BAND_LABELS: Record<string, string> = {
    under_100: 'Under ₹100',
    '100_200': '₹100 – ₹200',
    '200_500': '₹200 – ₹500',
    '500_plus': '₹500 and up',
};

export default function ShopType({
    typeSegment,
    products,
    filters,
    filterOptions,
    seo,
}: Props) {
    const heading = typeSegment.replace(/-/g, ' ');
    const hasActiveFilters = Object.values(filters).some(
        (value) => value !== null && value !== '',
    );
    const formAction = shopTypeRoute({ type: typeSegment }).url;

    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
                {seo.noindex && <meta name="robots" content="noindex" />}
            </Head>

            <div className="page-container space-y-8 py-7 sm:py-10">
                <header className="rounded-[2rem] border border-white/80 bg-linear-to-br from-violet-50 via-blue-50 to-amber-50 p-7 shadow-[0_20px_60px_-42px_rgba(15,35,80,0.48)] sm:p-10">
                    <p className="mb-2 text-sm font-bold text-primary">
                        BrightLearners shop
                    </p>
                    <h1 className="text-3xl font-black tracking-[-0.035em] capitalize sm:text-4xl">
                        {heading}
                    </h1>
                    <p className="mt-3 text-muted-foreground">
                        Thoughtfully designed printable resources for focused
                        practice at home.
                    </p>
                </header>

                <form
                    method="get"
                    action={formAction}
                    className="surface-card grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_1.35fr_auto] lg:items-end"
                >
                    <label className="flex flex-col gap-2 text-xs font-bold text-foreground/70">
                        Class
                        <select
                            name="class"
                            defaultValue={filters.class ?? ''}
                            className="field-control"
                        >
                            <option value="">All classes</option>
                            {filterOptions.classes.map((option) => (
                                <option key={option.id} value={option.slug}>
                                    {option.name}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label className="flex flex-col gap-2 text-xs font-bold text-foreground/70">
                        Subject
                        <select
                            name="subject"
                            defaultValue={filters.subject ?? ''}
                            className="field-control"
                        >
                            <option value="">All subjects</option>
                            {filterOptions.subjects.map((option) => (
                                <option key={option.id} value={option.slug}>
                                    {option.name}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label className="flex flex-col gap-2 text-xs font-bold text-foreground/70">
                        Price
                        <select
                            name="price_band"
                            defaultValue={filters.price_band ?? ''}
                            className="field-control"
                        >
                            <option value="">Any price</option>
                            {filterOptions.price_bands.map((band) => (
                                <option key={band} value={band}>
                                    {PRICE_BAND_LABELS[band] ?? band}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label className="flex flex-col gap-2 text-xs font-bold text-foreground/70">
                        Search
                        <input
                            type="text"
                            name="q"
                            defaultValue={filters.q ?? ''}
                            placeholder="Title or description"
                            className="field-control"
                        />
                    </label>

                    <Button type="submit" className="h-11">
                        Apply filters
                    </Button>
                    {hasActiveFilters && (
                        <Button asChild variant="ghost" type="button">
                            <a href={formAction}>Clear filters</a>
                        </Button>
                    )}
                </form>

                {products.data.length > 0 ? (
                    <>
                        <p className="mb-5 text-sm font-bold text-foreground/70">
                            {products.total} products
                        </p>
                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            {products.data.map((product) => (
                                <ProductCard
                                    key={product.id}
                                    product={product}
                                />
                            ))}
                        </div>
                        <Pagination
                            currentPage={products.current_page}
                            lastPage={products.last_page}
                            hrefForPage={(page) => {
                                const params = new URLSearchParams();

                                Object.entries(filters).forEach(
                                    ([key, value]) => {
                                        if (value) {
                                            params.set(key, value);
                                        }
                                    },
                                );

                                params.set('page', String(page));

                                return `${formAction}?${params.toString()}`;
                            }}
                        />
                    </>
                ) : (
                    <EmptyState
                        title="No products match these filters"
                        description="Try clearing the filters, or browse the full shop."
                        action={
                            hasActiveFilters ? (
                                <Button asChild variant="outline">
                                    <a href={formAction}>Clear filters</a>
                                </Button>
                            ) : undefined
                        }
                    />
                )}
            </div>
        </PublicLayout>
    );
}
