import { Link, usePage } from '@inertiajs/react';
import { Menu, Search, ShoppingCart } from 'lucide-react';
import { BrandLogo } from '@/components/public/brand-logo';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { cn } from '@/lib/utils';
import { home, login, register, search } from '@/routes';
import { dashboard } from '@/routes/account';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';
import { show as cartShow } from '@/routes/cart';

const disabledNavItems = [
    { label: 'Membership', reason: 'Coming soon' },
    { label: 'Parent Hub', reason: 'Coming soon' },
];

export function SiteHeader() {
    const { auth } = usePage().props;
    const { url } = usePage();
    const navItems = [
        { label: 'Home', href: home(), active: url === '/' },
        {
            label: 'Resources',
            href: freeIndex(),
            active: url.startsWith('/free') || url.startsWith('/class-'),
        },
        {
            label: 'Workbooks',
            href: shopIndex(),
            active: url.startsWith('/shop'),
        },
    ];

    return (
        <header className="sticky top-0 z-40 border-b border-border/70 bg-background/88 backdrop-blur-xl supports-backdrop-filter:bg-background/75">
            <div className="page-container flex h-14 items-center justify-between gap-4">
                <Link href={home()} aria-label="BrightLearners home">
                    <BrandLogo />
                </Link>

                <nav className="hidden items-center gap-6 text-sm font-semibold md:flex">
                    {navItems.map((item) => (
                        <Link
                            key={item.label}
                            href={item.href}
                            className={cn(
                                'relative py-2 text-foreground/65 transition-colors hover:text-foreground',
                                item.active &&
                                    'text-primary after:absolute after:inset-x-1 after:-bottom-2 after:h-0.5 after:rounded-full after:bg-primary',
                            )}
                        >
                            {item.label}
                        </Link>
                    ))}
                    {disabledNavItems.map((item) => (
                        <span
                            key={item.label}
                            title={item.reason}
                            className="cursor-not-allowed py-2 text-muted-foreground/70"
                        >
                            {item.label}
                        </span>
                    ))}
                </nav>

                <div className="hidden items-center gap-1.5 sm:flex">
                    <Link
                        href={search()}
                        aria-label="Search"
                        className="rounded-xl p-2.5 text-foreground/65 transition-colors hover:bg-accent hover:text-foreground"
                    >
                        <Search className="size-4" />
                    </Link>
                    <Link
                        href={cartShow()}
                        aria-label="Cart"
                        className="rounded-xl p-2.5 text-foreground/65 transition-colors hover:bg-accent hover:text-foreground"
                    >
                        <ShoppingCart className="size-4" />
                    </Link>

                    {auth.user ? (
                        <Button asChild size="sm" variant="outline">
                            <Link href={dashboard()}>My account</Link>
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

                <div className="flex items-center gap-1 sm:hidden">
                    <Link
                        href={search()}
                        aria-label="Search"
                        className="rounded-xl p-2.5 text-foreground/70 hover:bg-accent"
                    >
                        <Search className="size-5" />
                    </Link>
                    <Link
                        href={cartShow()}
                        aria-label="Cart"
                        className="rounded-xl p-2.5 text-foreground/70 hover:bg-accent"
                    >
                        <ShoppingCart className="size-5" />
                    </Link>
                    <Sheet>
                        <SheetTrigger asChild>
                            <Button
                                variant="ghost"
                                size="icon"
                                aria-label="Open menu"
                            >
                                <Menu className="size-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent className="w-[88%] rounded-l-3xl p-0">
                            <SheetHeader className="border-b px-6 py-5 text-left">
                                <SheetTitle>
                                    <BrandLogo />
                                </SheetTitle>
                            </SheetHeader>
                            <nav className="flex flex-col gap-2 p-5">
                                {navItems.map((item) => (
                                    <SheetClose key={item.label} asChild>
                                        <Link
                                            href={item.href}
                                            className={cn(
                                                'rounded-xl px-4 py-3 text-sm font-semibold text-foreground/75 hover:bg-accent',
                                                item.active &&
                                                    'bg-primary/10 text-primary',
                                            )}
                                        >
                                            {item.label}
                                        </Link>
                                    </SheetClose>
                                ))}
                                {disabledNavItems.map((item) => (
                                    <span
                                        key={item.label}
                                        className="px-4 py-3 text-sm font-semibold text-muted-foreground/60"
                                    >
                                        {item.label} · Coming soon
                                    </span>
                                ))}
                            </nav>
                            <div className="mt-auto grid gap-2 border-t p-5">
                                {auth.user ? (
                                    <Button asChild>
                                        <Link href={dashboard()}>
                                            My account
                                        </Link>
                                    </Button>
                                ) : (
                                    <>
                                        <Button asChild>
                                            <Link href={register()}>
                                                Create account
                                            </Link>
                                        </Button>
                                        <Button asChild variant="outline">
                                            <Link href={login()}>Log in</Link>
                                        </Button>
                                    </>
                                )}
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
        </header>
    );
}
