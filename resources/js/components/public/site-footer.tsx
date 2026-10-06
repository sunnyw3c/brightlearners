import { Link } from '@inertiajs/react';
import { Sun } from 'lucide-react';
import { home, search } from '@/routes';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';

export function SiteFooter() {
    return (
        <footer className="mt-16 border-t bg-muted/40">
            <div className="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 sm:flex-row sm:items-start sm:justify-between">
                <div className="space-y-2">
                    <Link
                        href={home()}
                        className="flex items-center gap-2 text-base font-bold text-foreground"
                    >
                        <span className="flex size-7 items-center justify-center rounded-full bg-highlight text-highlight-foreground">
                            <Sun className="size-4" />
                        </span>
                        BrightLearners
                    </Link>
                    <p className="max-w-xs text-sm text-muted-foreground">
                        Free worksheets, activities and workbooks for Class 1 to
                        Class 3 — built around what your child is actually
                        learning.
                    </p>
                </div>

                <nav className="flex flex-wrap gap-x-8 gap-y-3 text-sm">
                    <div className="space-y-2">
                        <p className="font-medium text-foreground">Learn</p>
                        <ul className="space-y-1.5 text-muted-foreground">
                            <li>
                                <Link
                                    href={freeIndex()}
                                    className="hover:text-foreground"
                                >
                                    Free resources
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href={shopIndex()}
                                    className="hover:text-foreground"
                                >
                                    Workbooks
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href={search()}
                                    className="hover:text-foreground"
                                >
                                    Search
                                </Link>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div className="border-t">
                <p className="mx-auto max-w-6xl px-4 py-4 text-xs text-muted-foreground">
                    &copy; {new Date().getFullYear()} BrightLearners. All rights
                    reserved.
                </p>
            </div>
        </footer>
    );
}
