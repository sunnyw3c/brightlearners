import { Head, Link } from '@inertiajs/react';
import { Search as SearchIcon } from 'lucide-react';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import { search } from '@/routes';
import type { Paginated, SearchResultData, SeoProps } from '@/types/public';

type Props = {
    q: string;
    results: Paginated<SearchResultData>;
    seo: SeoProps;
};

export default function SearchIndex({ q, results, seo }: Props) {
    return (
        <PublicLayout>
            <Head title={seo.title}>
                <meta name="robots" content="noindex" />
            </Head>

            <div className="mx-auto max-w-3xl space-y-6 px-4 py-8">
                <h1 className="text-2xl font-bold">Search</h1>

                <form
                    method="get"
                    action={search().url}
                    className="flex gap-2 rounded-2xl border bg-card p-3 shadow-sm"
                >
                    <div className="relative flex-1">
                        <SearchIcon className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                        <input
                            type="text"
                            name="q"
                            defaultValue={q}
                            placeholder="Search for a worksheet, activity or topic"
                            className="w-full rounded-xl border px-9 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        />
                    </div>
                    <Button type="submit">Search</Button>
                </form>

                {q === '' ? (
                    <EmptyState title="Type a search term to get started" />
                ) : results.data.length > 0 ? (
                    <>
                        <div className="space-y-3">
                            {results.data.map((result) => (
                                <Link key={result.url} href={result.url}>
                                    <div className="rounded-2xl border bg-card p-5 shadow-sm transition-shadow hover:shadow-md">
                                        <div className="flex items-center gap-2">
                                            {result.free && (
                                                <Badge className="border-transparent bg-success text-success-foreground">
                                                    FREE
                                                </Badge>
                                            )}
                                            {result.class && (
                                                <span className="text-xs text-muted-foreground">
                                                    {result.class}
                                                </span>
                                            )}
                                        </div>
                                        <p className="mt-2 text-base font-semibold">
                                            {result.title}
                                        </p>
                                        {result.summary && (
                                            <p className="mt-1 text-sm text-muted-foreground">
                                                {result.summary}
                                            </p>
                                        )}
                                    </div>
                                </Link>
                            ))}
                        </div>
                        <Pagination
                            currentPage={results.current_page}
                            lastPage={results.last_page}
                            hrefForPage={(page) =>
                                `${search().url}?q=${encodeURIComponent(q)}&page=${page}`
                            }
                        />
                    </>
                ) : (
                    <EmptyState
                        title={`No results for "${q}"`}
                        description="Try a different word, or browse the free resource hub."
                        action={
                            <Button asChild variant="outline">
                                <Link href="/free">Browse free resources</Link>
                            </Button>
                        }
                    />
                )}
            </div>
        </PublicLayout>
    );
}
