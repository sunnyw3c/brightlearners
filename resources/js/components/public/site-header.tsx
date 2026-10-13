import { Link, usePage } from '@inertiajs/react';
import { Search, ShoppingBag, Sun } from 'lucide-react';
import { home, login, register, search } from '@/routes';
import { index as freeIndex } from '@/routes/free';
import { index as shopIndex } from '@/routes/shop';
import { Button } from '@/components/ui/button';

export function SiteHeader() {
    const page = usePage();
    const { auth } = page.props;
    const url = page.url;

    const navItems = [
        { label: 'Home', href: home() },
        { label: 'Resources', href: freeIndex() },
        { label: 'Workbooks', href: shopIndex() },
        { label: 'Membership', href: '/membership' },
        { label: 'Parent Hub', href: '/parents' },
    ];

    return (
        <header className="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/95">
            <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
                {/* Brand Logo matching mockup */}
                <Link
                    href={home()}
                    className="flex items-center gap-2.5 text-lg font-black tracking-tight text-slate-900 dark:text-white"
                >
                    <span className="flex size-8.5 items-center justify-center rounded-full bg-[#FBBF24] text-amber-950 shadow-xs ring-2 ring-amber-300/40">
                        <Sun className="size-5 fill-amber-400 stroke-amber-900" />
                    </span>
                    <span>
                        Bright<span className="text-[#2563EB]">Learners</span>
                    </span>
                </Link>

                {/* Nav Links */}
                <nav className="hidden items-center gap-7 text-sm font-bold md:flex">
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
                <div className="flex items-center gap-2.5">
                    <Link
                        href={search()}
                        aria-label="Search"
                        className="flex size-9 items-center justify-center rounded-full text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                    >
                        <Search className="size-4.5" />
                    </Link>

                    <Link
                        href="/cart"
                        aria-label="Cart"
                        className="flex size-9 items-center justify-center rounded-full text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                    >
                        <ShoppingBag className="size-4.5" />
                    </Link>

                    {auth.user ? (
                        <Button asChild size="sm" variant="outline" className="rounded-full border-slate-200 font-bold px-4 dark:border-slate-800">
                            <Link href="/account/dashboard">My Account</Link>
                        </Button>
                    ) : (
                        <div className="flex items-center gap-2">
                            <Button asChild size="sm" variant="ghost" className="font-bold text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                                <Link href={login()}>Login</Link>
                            </Button>
                            <Button asChild size="sm" className="rounded-full bg-[#2563EB] px-5 font-bold text-white shadow-xs hover:bg-blue-700 transition-all">
                                <Link href={register()}>Sign Up</Link>
                            </Button>
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
}
