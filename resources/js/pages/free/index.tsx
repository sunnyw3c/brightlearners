import { Head } from '@inertiajs/react';
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

            <div className="mx-auto max-w-6xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-2xl font-semibold">
                        Free worksheets and activities
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Printable, free resources for Class 1 to Class 3.
                    </p>
                </header>

                <form
                    method="get"
                    action={freeIndexRoute().url}
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
                        Type
                        <select
                            name="type"
                            defaultValue={filters.type ?? ''}
                            className="rounded-md border px-2 py-1.5"
                        >
                            <option value="">All types</option>
                            {filterOptions.types.map((type) => (
                                <option key={type} value={type}>
                                    {type}
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
                            placeholder="Title or summary"
                            className="rounded-md border px-2 py-1.5"
                        />
                    </label>

                    <Button type="submit">Apply filters</Button>
                    {hasActiveFilters && (
                        <Button asChild variant="ghost" type="button">
                            <a href={freeIndexRoute.url()}>Clear filters</a>
                        </Button>
                    )}
                </form>

                {resources.data.length > 0 ? (
                    <>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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
