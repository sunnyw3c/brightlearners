import { Link } from '@inertiajs/react';
import { ArrowUpRight, Heart } from 'lucide-react';
import { BrandLogo } from '@/components/public/brand-logo';
import { home, search } from '@/routes';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';

export function SiteFooter() {
    return (
        <footer className="mt-20 border-t border-border/70 bg-white/65">
            <div className="page-container grid gap-10 py-12 md:grid-cols-[1.4fr_1fr_1fr]">
                <div className="space-y-4">
                    <Link href={home()} aria-label="BrightLearners home">
                        <BrandLogo />
                    </Link>
                    <p className="max-w-sm text-sm leading-6 text-muted-foreground">
                        Free worksheets, activities and workbooks for Class 1 to
                        Class 3 — built around what your child is actually
                        learning.
                    </p>
                </div>

                <nav className="text-sm">
                    <p className="mb-3 font-bold text-foreground">Explore</p>
                    <ul className="space-y-2.5 text-muted-foreground">
                        <li>
                            <Link
                                href={freeIndex()}
                                className="inline-flex items-center gap-1 hover:text-primary"
                            >
                                Free resources
                                <ArrowUpRight className="size-3.5" />
                            </Link>
                        </li>
                        <li>
                            <Link
                                href={shopIndex()}
                                className="hover:text-primary"
                            >
                                Workbooks
                            </Link>
                        </li>
                        <li>
                            <Link
                                href={search()}
                                className="hover:text-primary"
                            >
                                Search
                            </Link>
                        </li>
                    </ul>
                </nav>

                <div className="rounded-2xl bg-primary/7 p-5">
                    <p className="font-bold">Small steps. Bright futures.</p>
                    <p className="mt-2 text-sm leading-6 text-muted-foreground">
                        Thoughtful practice resources for confident learning at
                        home.
                    </p>
                </div>
            </div>

            <div className="border-t border-border/70">
                <div className="page-container flex flex-col gap-2 py-4 text-xs text-muted-foreground sm:flex-row sm:items-center sm:justify-between">
                    <p>
                        &copy; {new Date().getFullYear()} BrightLearners. All
                        rights reserved.
                    </p>
                    <p className="inline-flex items-center gap-1">
                        Made for curious young minds
                        <Heart className="size-3 fill-warm text-warm" />
                    </p>
                </div>
            </div>
        </footer>
    );
}
