import { Head, Link } from '@inertiajs/react';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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

            <div className="mx-auto max-w-4xl space-y-8 px-4 py-10 sm:px-6">
                <div className="text-center">
                    <p className="mb-2 text-sm font-bold text-primary">
                        Find the perfect next activity
                    </p>
                    <h1 className="text-3xl font-black tracking-[-0.035em] sm:text-4xl">
                        Search BrightLearners
                    </h1>
                </div>

                <form
                    method="get"
                    action={search().url}
                    className="surface-card flex gap-2 p-3"
                >
                    <input
                        type="text"
                        name="q"
                        defaultValue={q}
                        placeholder="Search for a worksheet, activity or topic"
                        className="field-control h-12 flex-1 border-0 bg-transparent shadow-none"
                    />
                    <Button type="submit" size="lg">
                        Search
                    </Button>
                </form>

                {q === '' ? (
                    <EmptyState title="Type a search term to get started" />
                ) : results.data.length > 0 ? (
                    <>
                        <div className="space-y-3">
                            {results.data.map((result) => (
                                <Link key={result.url} href={result.url}>
                                    <Card className="transition-shadow hover:shadow-md">
                                        <CardHeader>
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
                                            <CardTitle>
                                                {result.title}
                                            </CardTitle>
                                            {result.summary && (
                                                <CardDescription>
                                                    {result.summary}
                                                </CardDescription>
                                            )}
                                        </CardHeader>
                                    </Card>
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
