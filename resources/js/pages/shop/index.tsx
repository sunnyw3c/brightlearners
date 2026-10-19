import { Seo } from '@/components/seo';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { ProductCard } from '@/components/public/product-card';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { index as shopIndexRoute } from '@/routes/shop';
import type { Paginated, ProductCardData, SeoProps } from '@/types/public';

type FilterOption = { id: number; name: string; slug: string };
type TypeOption = { value: string; segment: string };

type Props = {
    products: Paginated<ProductCardData>;
    filters: {
        class: string | null;
        subject: string | null;
        type: string | null;
        price_band: string | null;
        q: string | null;
    };
    filterOptions: {
        classes: FilterOption[];
        subjects: FilterOption[];
        types: TypeOption[];
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

export default function ShopIndex({
    products,
    filters,
    filterOptions,
    seo,
}: Props) {
    const hasActiveFilters = Object.values(filters).some(
        (value) => value !== null && value !== '',
    );

    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-6xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-2xl font-semibold">Shop</h1>
                    <p className="text-sm text-muted-foreground">
                        Workbooks, topic packs, ebooks and bundles for Class 1
                        to Class 3.
                    </p>
                </header>

                <form
                    method="get"
                    action={shopIndexRoute().url}
                    className="rounded-3xl border border-slate-200/80 bg-card p-4 sm:p-5 shadow-xs dark:border-slate-800"
                >
                    <div className="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-5">
                        <label className="flex flex-col gap-1.5 text-xs font-bold text-foreground">
                            <span>Class</span>
                            <select
                                name="class"
                                defaultValue={filters.class ?? ''}
                                className="w-full rounded-xl border border-slate-200 bg-background px-3 py-2 text-sm text-foreground shadow-2xs focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 dark:border-slate-700 dark:bg-slate-900"
                            >
                                <option value="">All classes</option>
                                {filterOptions.classes.map((option) => (
                                    <option key={option.id} value={option.slug}>
                                        {option.name}
                                    </option>
                                ))}
                            </select>
                        </label>

                        <label className="flex flex-col gap-1.5 text-xs font-bold text-foreground">
                            <span>Subject</span>
                            <select
                                name="subject"
                                defaultValue={filters.subject ?? ''}
                                className="w-full rounded-xl border border-slate-200 bg-background px-3 py-2 text-sm text-foreground shadow-2xs focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 dark:border-slate-700 dark:bg-slate-900"
                            >
                                <option value="">All subjects</option>
                                {filterOptions.subjects.map((option) => (
                                    <option key={option.id} value={option.slug}>
                                        {option.name}
                                    </option>
                                ))}
                            </select>
                        </label>

                        <label className="flex flex-col gap-1.5 text-xs font-bold text-foreground">
                            <span>Type</span>
                            <select
                                name="type"
                                defaultValue={filters.type ?? ''}
                                className="w-full rounded-xl border border-slate-200 bg-background px-3 py-2 text-sm text-foreground shadow-2xs focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 dark:border-slate-700 dark:bg-slate-900"
                            >
                                <option value="">All types</option>
                                {filterOptions.types.map((type) => (
                                    <option key={type.segment} value={type.segment}>
                                        {type.value.replace('_', ' ')}
                                    </option>
                                ))}
                            </select>
                        </label>

                        <label className="flex flex-col gap-1.5 text-xs font-bold text-foreground">
                            <span>Price</span>
                            <select
                                name="price_band"
                                defaultValue={filters.price_band ?? ''}
                                className="w-full rounded-xl border border-slate-200 bg-background px-3 py-2 text-sm text-foreground shadow-2xs focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 dark:border-slate-700 dark:bg-slate-900"
                            >
                                <option value="">Any price</option>
                                {filterOptions.price_bands.map((band) => (
                                    <option key={band} value={band}>
                                        {PRICE_BAND_LABELS[band] ?? band}
                                    </option>
                                ))}
                            </select>
                        </label>

                        <label className="flex flex-col gap-1.5 text-xs font-bold text-foreground">
                            <span>Search</span>
                            <input
                                type="text"
                                name="q"
                                defaultValue={filters.q ?? ''}
                                placeholder="Title or description"
                                className="w-full rounded-xl border border-slate-200 bg-background px-3 py-2 text-sm text-foreground shadow-2xs placeholder:text-muted-foreground focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary/20 dark:border-slate-700 dark:bg-slate-900"
                            />
                        </label>
                    </div>

                    <div className="mt-4 flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <Button
                            type="submit"
                            className="rounded-full bg-[#2563EB] px-5 font-bold text-white shadow-xs hover:bg-blue-700"
                        >
                            Apply filters
                        </Button>
                        {hasActiveFilters && (
                            <Button asChild variant="ghost" type="button" className="rounded-full">
                                <a href={shopIndexRoute.url()}>Clear filters</a>
                            </Button>
                        )}
                    </div>
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

                                return `${shopIndexRoute.url()}?${params.toString()}`;
                            }}
                        />
                    </>
                ) : (
                    <EmptyState
                        title="No products match these filters"
                        description="Try clearing the filters, or browse a product type from the menu."
                        action={
                            hasActiveFilters ? (
                                <Button asChild variant="outline">
                                    <a href={shopIndexRoute.url()}>
                                        Clear filters
                                    </a>
                                </Button>
                            ) : undefined
                        }
                    />
                )}
            </div>
        </PublicLayout>
    );
}
