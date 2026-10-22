import { Link } from '@inertiajs/react';
import { KidDozenLogo } from '@/components/brand/kid-dozen-logo';
import { home, search } from '@/routes';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';

export function SiteFooter() {
    return (
        <footer className="mt-16 border-t bg-muted/40">
            <div className="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 sm:flex-row sm:items-start sm:justify-between">
                <div className="space-y-3">
                    <KidDozenLogo size="md" showTagline />
                    <p className="max-w-xs text-sm text-muted-foreground">
                        Free worksheets, playful activities and curated workbooks for Class 1 to
                        Class 3 — making everyday learning joyful with KiddoZen.
                    </p>
                </div>

                <nav className="grid grid-cols-2 gap-8 text-sm sm:grid-cols-3">
                    <div className="space-y-2.5">
                        <p className="font-bold text-foreground">Learn</p>
                        <ul className="space-y-2 text-muted-foreground">
                            <li>
                                <Link
                                    href={freeIndex()}
                                    className="transition-colors hover:text-foreground"
                                >
                                    Free resources
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/coloring"
                                    className="transition-colors hover:text-foreground"
                                >
                                    Coloring pages & books
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href={shopIndex()}
                                    className="transition-colors hover:text-foreground"
                                >
                                    Workbooks
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href={search()}
                                    className="transition-colors hover:text-foreground"
                                >
                                    Search library
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div className="space-y-2.5">
                        <p className="font-bold text-foreground">Programmes</p>
                        <ul className="space-y-2 text-muted-foreground">
                            <li>
                                <Link
                                    href="/membership"
                                    className="transition-colors hover:text-foreground"
                                >
                                    Weekly Programme
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/membership/sample-month"
                                    className="transition-colors hover:text-foreground"
                                >
                                    Sample Month
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/cart"
                                    className="transition-colors hover:text-foreground"
                                >
                                    View Cart
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div className="space-y-2.5">
                        <p className="font-bold text-foreground">Parents</p>
                        <ul className="space-y-2 text-muted-foreground">
                            <li>
                                <Link
                                    href="/parents"
                                    className="transition-colors hover:text-foreground"
                                >
                                    Parent Hub
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/account/dashboard"
                                    className="transition-colors hover:text-foreground"
                                >
                                    Parent Dashboard
                                </Link>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div className="border-t">
                <p className="mx-auto max-w-6xl px-4 py-4 text-xs text-muted-foreground">
                    &copy; {new Date().getFullYear()} KiddoZen. All rights
                    reserved.
                </p>
            </div>
        </footer>
    );
}
