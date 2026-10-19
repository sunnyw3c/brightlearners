import { Seo } from '@/components/seo';
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
            <Seo seo={seo} />

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
                    className="rounded-3xl border border-slate-200/80 bg-card p-4 sm:p-5 shadow-xs dark:border-slate-800"
                >
                    <div className="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
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
                                    <option key={type} value={type}>
                                        {type}
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
                                placeholder="Title or summary"
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
                                <a href={freeIndexRoute.url()}>Clear filters</a>
                            </Button>
                        )}
                    </div>
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
