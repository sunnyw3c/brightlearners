import { Link, usePage } from '@inertiajs/react';
import { Search, Sun } from 'lucide-react';
import { home, login, register, search } from '@/routes';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';
import { Button } from '@/components/ui/button';

const disabledNavItems = [
    { label: 'Membership', reason: 'Coming soon' },
    { label: 'Parent Hub', reason: 'Coming soon' },
];

export function SiteHeader() {
    const { auth } = usePage().props;

    return (
        <header className="sticky top-0 z-40 border-b bg-background/95 backdrop-blur supports-backdrop-filter:bg-background/80">
            <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
                <Link
                    href={home()}
                    className="flex items-center gap-2 text-lg font-bold tracking-tight text-foreground"
                >
                    <span className="flex size-8 items-center justify-center rounded-full bg-highlight text-highlight-foreground">
                        <Sun className="size-5" />
                    </span>
                    BrightLearners
                </Link>

                <nav className="hidden items-center gap-6 text-sm font-medium md:flex">
                    <Link
                        href={home()}
                        className="text-foreground/70 transition-colors hover:text-foreground"
                    >
                        Home
                    </Link>
                    <Link
                        href={freeIndex()}
                        className="text-foreground/70 transition-colors hover:text-foreground"
                    >
                        Resources
                    </Link>
                    <Link
                        href={shopIndex()}
                        className="text-foreground/70 transition-colors hover:text-foreground"
                    >
                        Workbooks
                    </Link>
                    {disabledNavItems.map((item) => (
                        <span
                            key={item.label}
                            title={item.reason}
                            className="cursor-not-allowed text-muted-foreground"
                        >
                            {item.label}
                        </span>
                    ))}
                </nav>

                <div className="flex items-center gap-2">
                    <Link
                        href={search()}
                        aria-label="Search"
                        className="rounded-full p-2 text-foreground/70 transition-colors hover:bg-accent hover:text-foreground"
                    >
                        <Search className="size-4" />
                    </Link>

                    {auth.user ? (
                        <Button asChild size="sm" variant="outline">
                            <Link href="/account/dashboard">My account</Link>
                        </Button>
                    ) : (
                        <>
                            <Button asChild size="sm" variant="ghost">
                                <Link href={login()}>Login</Link>
                            </Button>
                            <Button asChild size="sm">
                                <Link href={register()}>Sign Up</Link>
                            </Button>
                        </>
                    )}
                </div>
            </div>
        </header>
    );
}
