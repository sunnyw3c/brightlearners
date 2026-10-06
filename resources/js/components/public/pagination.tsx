import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

export function Pagination({
    currentPage,
    lastPage,
    hrefForPage,
}: {
    currentPage: number;
    lastPage: number;
    hrefForPage: (page: number) => string;
}) {
    if (lastPage <= 1) {
        return null;
    }

    return (
        <nav
            aria-label="Pagination"
            className="flex items-center justify-center gap-2"
        >
            <Button
                asChild={currentPage > 1}
                variant="outline"
                size="sm"
                disabled={currentPage <= 1}
            >
                {currentPage > 1 ? (
                    <Link href={hrefForPage(currentPage - 1)}>Previous</Link>
                ) : (
                    <span>Previous</span>
                )}
            </Button>

            <span className="text-sm text-muted-foreground">
                Page {currentPage} of {lastPage}
            </span>

            <Button
                asChild={currentPage < lastPage}
                variant="outline"
                size="sm"
                disabled={currentPage >= lastPage}
            >
                {currentPage < lastPage ? (
                    <Link href={hrefForPage(currentPage + 1)}>Next</Link>
                ) : (
                    <span>Next</span>
                )}
            </Button>
        </nav>
    );
}
