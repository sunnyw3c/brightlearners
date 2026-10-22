import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import {
    BookOpen,
    Compass,
    Home as HomeIcon,
    Menu,
    Moon,
    Palette,
    Search,
    ShoppingBag,
    Sparkles,
    Sun,
    User,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { useAppearance } from '@/hooks/use-appearance';
import { KidDozenLogo } from '@/components/brand/kid-dozen-logo';
import { home, login, register, search } from '@/routes';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';

export function SiteHeader() {
    const page = usePage();
    const { auth } = page.props;
    const url = page.url;
    const { resolvedAppearance, updateAppearance } = useAppearance();
    const [mobileOpen, setMobileOpen] = useState(false);

    const toggleTheme = () => {
        updateAppearance(resolvedAppearance === 'dark' ? 'light' : 'dark');
    };

    const navItems = [
        { label: 'Home', href: home().url, icon: HomeIcon },
        { label: 'Resources', href: freeIndex().url, icon: BookOpen },
        { label: 'Coloring', href: '/coloring', icon: Palette },
        { label: 'Workbooks', href: shopIndex().url, icon: ShoppingBag },
        { label: 'Membership', href: '/membership', icon: Sparkles },
        { label: 'Parent Hub', href: '/parents', icon: Compass },
    ];

    return (
        <header className="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/95">
            <div className="mx-auto flex max-w-6xl items-center justify-between gap-2 px-3 py-3 sm:gap-4 sm:px-4">
                {/* KiddoZen Brand Logo with interactive kid mascot */}
                <KidDozenLogo size="md" />

                {/* Desktop Nav Links */}
                <nav className="hidden items-center gap-6 text-sm font-bold md:flex lg:gap-7">
                    {navItems.map((item) => {
                        const isActive =
                            item.href === '/'
                                ? url === '/'
                                : url.startsWith(item.href);

                        return (
                            <Link
                                key={item.label}
                                href={item.href}
                                className={`transition-colors hover:text-primary ${
                                    isActive
                                        ? 'text-primary'
                                        : 'text-slate-600 dark:text-slate-300'
                                }`}
                            >
                                {item.label}
                            </Link>
                        );
                    })}
                </nav>

                {/* Right Actions */}
                <div className="flex items-center gap-1.5 sm:gap-2.5">
                    <Link
                        href={search()}
                        aria-label="Search"
                        className="flex size-8.5 items-center justify-center rounded-full text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 sm:size-9 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                    >
                        <Search className="size-4 sm:size-4.5" />
                    </Link>

                    <Link
                        href="/cart"
                        aria-label="Cart"
                        className="flex size-8.5 items-center justify-center rounded-full text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 sm:size-9 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                    >
                        <ShoppingBag className="size-4 sm:size-4.5" />
                    </Link>

                    {/* Dark / Light Theme Toggle Button */}
                    <button
                        id="theme-toggle"
                        type="button"
                        onClick={toggleTheme}
                        aria-label={`Switch to ${resolvedAppearance === 'dark' ? 'light' : 'dark'} mode`}
                        title={`Switch to ${resolvedAppearance === 'dark' ? 'light' : 'dark'} mode`}
                        className="flex size-8.5 items-center justify-center rounded-full text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 sm:size-9 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                    >
                        {resolvedAppearance === 'dark' ? (
                            <Sun className="size-4 text-amber-400 sm:size-4.5" />
                        ) : (
                            <Moon className="size-4 text-slate-600 sm:size-4.5" />
                        )}
                    </button>

                    {/* Desktop Auth CTA */}
                    {auth.user ? (
                        <Button
                            asChild
                            size="sm"
                            variant="outline"
                            className="hidden rounded-full border-slate-200 px-4 font-bold sm:inline-flex dark:border-slate-800"
                        >
                            <Link href="/account/dashboard">My Account</Link>
                        </Button>
                    ) : (
                        <div className="hidden items-center gap-2 sm:flex">
                            <Button
                                asChild
                                size="sm"
                                variant="ghost"
                                className="font-bold text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
                            >
                                <Link href={login()}>Login</Link>
                            </Button>
                            <Button
                                asChild
                                size="sm"
                                className="rounded-full bg-[#2563EB] px-4 font-bold text-white shadow-xs transition-all hover:bg-blue-700 sm:px-5"
                            >
                                <Link href={register()}>Sign Up</Link>
                            </Button>
                        </div>
                    )}

                    {/* Mobile Hamburger Drawer */}
                    <Sheet open={mobileOpen} onOpenChange={setMobileOpen}>
                        <SheetTrigger asChild>
                            <button
                                type="button"
                                aria-label="Open mobile menu"
                                className="flex size-8.5 items-center justify-center rounded-xl border border-slate-200 text-slate-700 transition-colors hover:bg-slate-100 md:hidden sm:size-9 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                            >
                                <Menu className="size-5" />
                            </button>
                        </SheetTrigger>
                        <SheetContent
                            side="right"
                            className="flex w-[82vw] max-w-sm flex-col p-6 dark:bg-slate-900"
                        >
                            <SheetHeader className="text-left">
                                <div className="flex items-center pb-2 pr-6">
                                    <KidDozenLogo size="md" asLink={false} />
                                </div>
                                <SheetTitle className="sr-only">Navigation Menu</SheetTitle>
                            </SheetHeader>

                            {/* User Section in Mobile Menu */}
                            <div className="mt-4 rounded-2xl border border-slate-200/80 bg-slate-50/70 p-3.5 dark:border-slate-800 dark:bg-slate-800/40">
                                {auth.user ? (
                                    <div className="flex items-center justify-between gap-3">
                                        <div className="flex items-center gap-2.5 truncate">
                                            <div className="flex size-9 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                                <User className="size-4.5" />
                                            </div>
                                            <div className="truncate">
                                                <p className="truncate text-xs font-bold text-slate-900 dark:text-white">
                                                    {auth.user.name}
                                                </p>
                                                <p className="truncate text-[11px] text-muted-foreground">
                                                    {auth.user.email}
                                                </p>
                                            </div>
                                        </div>
                                        <Button
                                            asChild
                                            size="sm"
                                            className="rounded-full bg-[#2563EB] text-xs font-bold text-white hover:bg-blue-700"
                                        >
                                            <Link
                                                href="/account/dashboard"
                                                onClick={() => setMobileOpen(false)}
                                            >
                                                Dashboard
                                            </Link>
                                        </Button>
                                    </div>
                                ) : (
                                    <div className="flex flex-col gap-2">
                                        <Button
                                            asChild
                                            size="sm"
                                            className="w-full rounded-full bg-[#2563EB] font-bold text-white hover:bg-blue-700"
                                        >
                                            <Link
                                                href={register()}
                                                onClick={() => setMobileOpen(false)}
                                            >
                                                Create Free Account
                                            </Link>
                                        </Button>
                                        <Button
                                            asChild
                                            size="sm"
                                            variant="outline"
                                            className="w-full rounded-full border-slate-200 font-bold dark:border-slate-700"
                                        >
                                            <Link
                                                href={login()}
                                                onClick={() => setMobileOpen(false)}
                                            >
                                                Login
                                            </Link>
                                        </Button>
                                    </div>
                                )}
                            </div>

                            {/* Nav Links in Mobile Menu */}
                            <nav className="mt-6 flex flex-1 flex-col space-y-1.5 overflow-y-auto">
                                <p className="mb-2 px-2 text-xs font-bold uppercase tracking-wider text-muted-foreground">
                                    Navigation
                                </p>
                                {navItems.map((item) => {
                                    const IconComponent = item.icon;
                                    const isActive =
                                        item.href === '/'
                                            ? url === '/'
                                            : url.startsWith(item.href);

                                    return (
                                        <Link
                                            key={item.label}
                                            href={item.href}
                                            onClick={() => setMobileOpen(false)}
                                            className={`flex items-center gap-3 rounded-2xl px-3.5 py-3 text-sm font-bold transition-all ${
                                                isActive
                                                    ? 'bg-blue-50 text-[#2563EB] dark:bg-blue-950/50 dark:text-blue-400'
                                                    : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
                                            }`}
                                        >
                                            <span
                                                className={`flex size-8 items-center justify-center rounded-xl ${
                                                    isActive
                                                        ? 'bg-blue-100 text-[#2563EB] dark:bg-blue-900/60 dark:text-blue-300'
                                                        : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'
                                                }`}
                                            >
                                                <IconComponent className="size-4" />
                                            </span>
                                            <span>{item.label}</span>
                                        </Link>
                                    );
                                })}

                                <div className="pt-4">
                                    <p className="mb-2 px-2 text-xs font-bold uppercase tracking-wider text-muted-foreground">
                                        Quick Links
                                    </p>
                                    <Link
                                        href={search()}
                                        onClick={() => setMobileOpen(false)}
                                        className="flex items-center gap-3 rounded-2xl px-3.5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                                    >
                                        <Search className="size-4" />
                                        <span>Search Worksheets</span>
                                    </Link>
                                    <Link
                                        href="/cart"
                                        onClick={() => setMobileOpen(false)}
                                        className="flex items-center gap-3 rounded-2xl px-3.5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                                    >
                                        <ShoppingBag className="size-4" />
                                        <span>View Cart</span>
                                    </Link>
                                </div>
                            </nav>

                            {/* Mobile Drawer Bottom Theme Toggle */}
                            <div className="mt-auto border-t border-slate-200/80 pt-4 dark:border-slate-800">
                                <div className="flex items-center justify-between px-2">
                                    <span className="text-xs font-semibold text-muted-foreground">
                                        Theme: {resolvedAppearance === 'dark' ? 'Dark Mode' : 'Light Mode'}
                                    </span>
                                    <button
                                        type="button"
                                        onClick={toggleTheme}
                                        className="flex items-center gap-1.5 rounded-full border border-slate-200 px-3 py-1 text-xs font-bold text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                                    >
                                        {resolvedAppearance === 'dark' ? (
                                            <>
                                                <Sun className="size-3.5 text-amber-400" />
                                                <span>Light</span>
                                            </>
                                        ) : (
                                            <>
                                                <Moon className="size-3.5 text-slate-600" />
                                                <span>Dark</span>
                                            </>
                                        )}
                                    </button>
                                </div>
                                <p className="mt-3 text-center text-[11px] text-muted-foreground">
                                    Classes 1 to 3 • CBSE & ICSE Aligned
                                </p>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
        </header>
    );
}
