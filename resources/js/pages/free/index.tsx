import { Head } from '@inertiajs/react';
import { Gift, Search, SlidersHorizontal, Sparkles } from 'lucide-react';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { ResourceCard } from '@/components/public/resource-card';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { index as freeIndexRoute } from '@/routes/free';
import type { Paginated, ResourceCardData, SeoProps } from '@/types/public';

type FilterOption = { id: number; name: string; slug: string };

type Props = {
    resources: Paginated<ResourceCardData>;
    filters: {
        class: string | null;
        subject: string | null;
        topic: string | null;
        type: string | null;
        difficulty: string | null;
        price: string | null;
        q: string | null;
    };
    filterOptions: {
        classes: FilterOption[];
        subjects: FilterOption[];
        topics: FilterOption[];
        types: string[];
    };
    seo: SeoProps;
};

export default function FreeResourceIndex({
    resources,
    filters,
    filterOptions,
    seo,
}: Props) {
    const hasActiveFilters = Object.values(filters).some(
        (value) => value !== null && value !== '',
    );

    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="description" content={seo.description ?? ''} />
                <link rel="canonical" href={seo.canonical} />
                {seo.noindex && <meta name="robots" content="noindex" />}
            </Head>

            <div className="page-container space-y-8 py-7 sm:py-10">
                <header className="relative overflow-hidden rounded-[2rem] border border-white/80 bg-linear-to-br from-blue-50 via-sky-50 to-emerald-50 p-7 shadow-[0_20px_60px_-42px_rgba(15,35,80,0.48)] sm:p-10">
                    <span className="absolute -top-10 right-8 flex size-32 rotate-6 items-center justify-center rounded-[2rem] bg-white/75 text-success shadow-sm">
                        <Gift className="size-14" strokeWidth={1.5} />
                    </span>
                    <div className="relative max-w-2xl pr-20 sm:pr-32">
                        <span className="eyebrow">
                            <Sparkles className="size-3.5" /> Ready-to-use
                            learning
                        </span>
                        <h1 className="mt-4 text-3xl font-black tracking-[-0.035em] sm:text-4xl">
                            Free worksheets and activities
                        </h1>
                        <p className="mt-3 leading-7 text-muted-foreground">
                            Printable, free resources for Class 1 to Class 3.
                        </p>
                    </div>
                </header>

                <form
                    method="get"
                    action={freeIndexRoute().url}
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
                        Type
                        <select
                            name="type"
                            defaultValue={filters.type ?? ''}
                            className="field-control capitalize"
                        >
                            <option value="">All types</option>
                            {filterOptions.types.map((type) => (
                                <option key={type} value={type}>
                                    {type}
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
                            placeholder="Title or summary"
                            className="field-control"
                        />
                    </label>

                    <Button type="submit" className="h-11">
                        <SlidersHorizontal className="size-4" /> Apply
                    </Button>
                    {hasActiveFilters && (
                        <Button
                            asChild
                            variant="ghost"
                            type="button"
                            className="lg:col-start-5"
                        >
                            <a href={freeIndexRoute.url()}>Clear filters</a>
                        </Button>
                    )}
                </form>

                {resources.data.length > 0 ? (
                    <>
                        <div className="mb-5 flex items-center justify-between">
                            <p className="text-sm font-bold text-foreground/70">
                                {resources.total} resources
                            </p>
                            <Search className="size-4 text-muted-foreground" />
                        </div>
                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            {resources.data.map((resource) => (
                                <ResourceCard
                                    key={resource.id}
                                    resource={resource}
                                />
                            ))}
                        </div>
                        <Pagination
                            currentPage={resources.current_page}
                            lastPage={resources.last_page}
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

                                return `${freeIndexRoute.url()}?${params.toString()}`;
                            }}
                        />
                    </>
                ) : (
                    <EmptyState
                        title="No resources match these filters"
                        description="Try clearing the filters, or browse a class page for suggestions."
                        action={
                            hasActiveFilters ? (
                                <Button asChild variant="outline">
                                    <a href={freeIndexRoute.url()}>
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
