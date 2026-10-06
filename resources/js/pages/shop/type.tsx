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

            <div className="mx-auto max-w-6xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-2xl font-semibold capitalize">
                        {heading}
                    </h1>
                </header>

                <form
                    method="get"
                    action={formAction}
                    className="flex flex-wrap items-end gap-3 rounded-2xl border bg-card p-5 shadow-sm"
                >
                    <label className="flex flex-col gap-1 text-sm">
                        Class
                        <select
                            name="class"
                            defaultValue={filters.class ?? ''}
                            className="rounded-md border px-2 py-1.5"
                        >
                            <option value="">All classes</option>
                            {filterOptions.classes.map((option) => (
                                <option key={option.id} value={option.slug}>
                                    {option.name}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label className="flex flex-col gap-1 text-sm">
                        Subject
                        <select
                            name="subject"
                            defaultValue={filters.subject ?? ''}
                            className="rounded-md border px-2 py-1.5"
                        >
                            <option value="">All subjects</option>
                            {filterOptions.subjects.map((option) => (
                                <option key={option.id} value={option.slug}>
                                    {option.name}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label className="flex flex-col gap-1 text-sm">
                        Price
                        <select
                            name="price_band"
                            defaultValue={filters.price_band ?? ''}
                            className="rounded-md border px-2 py-1.5"
                        >
                            <option value="">Any price</option>
                            {filterOptions.price_bands.map((band) => (
                                <option key={band} value={band}>
                                    {PRICE_BAND_LABELS[band] ?? band}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label className="flex flex-col gap-1 text-sm">
                        Search
                        <input
                            type="text"
                            name="q"
                            defaultValue={filters.q ?? ''}
                            placeholder="Title or description"
                            className="rounded-md border px-2 py-1.5"
                        />
                    </label>

                    <Button type="submit">Apply filters</Button>
                    {hasActiveFilters && (
                        <Button asChild variant="ghost" type="button">
                            <a href={formAction}>Clear filters</a>
                        </Button>
                    )}
                </form>

                {products.data.length > 0 ? (
                    <>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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
