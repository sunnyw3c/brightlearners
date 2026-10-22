import { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import {
    Binary,
    Car,
    CheckCircle2,
    Download,
    Flower2,
    Palette,
    PawPrint,
    Printer,
    Rocket,
    Search,
    ShoppingBag,
    Sparkles,
    Type,
    X,
} from 'lucide-react';
import { Seo } from '@/components/seo';
import { EmptyState } from '@/components/public/empty-state';
import { Pagination } from '@/components/public/pagination';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import type { Paginated, ProductCardData, ResourceCardData, SeoProps } from '@/types/public';

type SchoolClassOption = { id: number; name: string; slug: string };

type ThemeOption = {
    slug: string;
    name: string;
    description: string;
    icon: string;
};

type Props = {
    resources: Paginated<ResourceCardData>;
    filters: {
        theme: string | null;
        class: string | null;
        q: string | null;
    };
    themes: ThemeOption[];
    classes: SchoolClassOption[];
    featuredWorkbooks: ProductCardData[];
    seo: SeoProps;
};

const THEME_ICONS: Record<string, React.ComponentType<{ className?: string }>> = {
    PawPrint,
    Type,
    Binary,
    Flower2,
    Car,
    Sparkles,
    Rocket,
};

export default function ColoringIndex({
    resources,
    filters,
    themes,
    classes,
    featuredWorkbooks,
    seo,
}: Props) {
    const [searchTerm, setSearchTerm] = useState(filters.q ?? '');

    const activeTheme = filters.theme ?? '';
    const activeClass = filters.class ?? '';

    const handleSearchSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(
            '/coloring',
            {
                theme: activeTheme || undefined,
                class: activeClass || undefined,
                q: searchTerm.trim() || undefined,
            },
            { preserveState: true },
        );
    };

    const handleThemeClick = (themeSlug: string) => {
        const nextTheme = activeTheme === themeSlug ? undefined : themeSlug;
        router.get(
            '/coloring',
            {
                theme: nextTheme,
                class: activeClass || undefined,
                q: filters.q || undefined,
            },
            { preserveState: true },
        );
    };

    const handleClassClick = (classSlug: string) => {
        const nextClass = activeClass === classSlug ? undefined : classSlug;
        router.get(
            '/coloring',
            {
                theme: activeTheme || undefined,
                class: nextClass,
                q: filters.q || undefined,
            },
            { preserveState: true },
        );
    };

    const handleClearFilters = () => {
        setSearchTerm('');
        router.get('/coloring');
    };

    const hasActiveFilters = Boolean(activeTheme || activeClass || filters.q);

    return (
        <PublicLayout>
            <Seo seo={seo} />

            {/* Vibrant Hero Section */}
            <div className="relative overflow-hidden border-b border-pink-100 bg-gradient-to-b from-pink-50/60 via-purple-50/40 to-white py-12 sm:py-16 dark:border-slate-800 dark:from-slate-900 dark:via-purple-950/20 dark:to-slate-950">
                <div className="absolute -left-12 -top-12 size-72 rounded-full bg-pink-200/40 blur-3xl dark:bg-pink-900/10" />
                <div className="absolute -right-12 top-1/4 size-80 rounded-full bg-amber-200/40 blur-3xl dark:bg-amber-900/10" />

                <div className="relative mx-auto max-w-6xl px-4 text-center">
                    <div className="inline-flex items-center gap-2 rounded-full border border-pink-200 bg-pink-100/70 px-4 py-1.5 text-xs font-bold text-pink-700 backdrop-blur-xs dark:border-pink-900/60 dark:bg-pink-950/60 dark:text-pink-300">
                        <Palette className="size-4 animate-bounce" />
                        <span>KiddoZen Creative Arts Hub</span>
                    </div>

                    <h1 className="mt-4 text-3xl font-black tracking-tight text-slate-900 sm:text-5xl dark:text-white">
                        Free Printable Coloring Pages & Books
                    </h1>

                    <p className="mx-auto mt-4 max-w-2xl text-base text-slate-600 sm:text-lg dark:text-slate-300">
                        Crisp, bold-outline vector coloring sheets designed for little artists.
                        Build pencil grip, fine motor coordination, phonics, and focus with zero
                        fuss — instant 1-click PDF download.
                    </p>

                    {/* Search Bar */}
                    <form
                        onSubmit={handleSearchSubmit}
                        className="mx-auto mt-7 flex max-w-xl items-center gap-2 rounded-full border border-slate-200 bg-white p-1.5 shadow-md transition-all focus-within:border-pink-400 focus-within:ring-2 focus-within:ring-pink-200 dark:border-slate-700 dark:bg-slate-900"
                    >
                        <div className="flex flex-1 items-center gap-2 px-3">
                            <Search className="size-4 text-slate-400" />
                            <input
                                type="text"
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                placeholder="Search by name, animal, letter, or theme (e.g. Elephant, Phonics)..."
                                className="w-full bg-transparent text-sm text-foreground placeholder:text-muted-foreground focus:outline-hidden"
                            />
                            {searchTerm && (
                                <button
                                    type="button"
                                    onClick={() => setSearchTerm('')}
                                    className="text-slate-400 hover:text-slate-600"
                                >
                                    <X className="size-4" />
                                </button>
                            )}
                        </div>
                        <Button
                            type="submit"
                            size="sm"
                            className="rounded-full bg-gradient-to-r from-pink-500 to-purple-600 font-bold text-white shadow-xs hover:from-pink-600 hover:to-purple-700"
                        >
                            Search
                        </Button>
                    </form>

                    {/* Quick Stats Banner */}
                    <div className="mt-8 flex flex-wrap items-center justify-center gap-6 text-xs font-semibold text-slate-600 sm:gap-10 dark:text-slate-400">
                        <div className="flex items-center gap-1.5">
                            <CheckCircle2 className="size-4 text-emerald-500" />
                            <span>100% Free Instant Downloads</span>
                        </div>
                        <div className="flex items-center gap-1.5">
                            <CheckCircle2 className="size-4 text-emerald-500" />
                            <span>A4 Standard Print-Ready</span>
                        </div>
                        <div className="flex items-center gap-1.5">
                            <CheckCircle2 className="size-4 text-emerald-500" />
                            <span>Eco / Low-Ink Friendly</span>
                        </div>
                    </div>
                </div>
            </div>

            {/* Theme & Filter Bar */}
            <div className="sticky top-[57px] z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/95">
                <div className="mx-auto max-w-6xl px-4 py-3">
                    <div className="flex flex-col gap-3">
                        {/* Themes Horizontal Scroll */}
                        <div className="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                            <button
                                type="button"
                                onClick={() => handleThemeClick('')}
                                className={`flex shrink-0 items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-bold transition-all ${
                                    !activeTheme
                                        ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs'
                                        : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300'
                                }`}
                            >
                                <Sparkles className="size-3.5" />
                                <span>All Themes</span>
                            </button>

                            {themes.map((theme) => {
                                const IconComponent = THEME_ICONS[theme.icon] || Palette;
                                const isSelected = activeTheme === theme.slug;

                                return (
                                    <button
                                        key={theme.slug}
                                        type="button"
                                        onClick={() => handleThemeClick(theme.slug)}
                                        className={`flex shrink-0 items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-bold transition-all ${
                                            isSelected
                                                ? 'bg-pink-600 text-white shadow-xs'
                                                : 'bg-slate-100 text-slate-700 hover:bg-pink-50 hover:text-pink-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-pink-950/40'
                                        }`}
                                    >
                                        <IconComponent className="size-3.5" />
                                        <span>{theme.name}</span>
                                    </button>
                                );
                            })}
                        </div>

                        {/* Class Filter Bar */}
                        <div className="flex flex-wrap items-center justify-between gap-2 pt-1">
                            <div className="flex items-center gap-2">
                                <span className="text-xs font-bold uppercase tracking-wider text-muted-foreground">
                                    Age / Class:
                                </span>
                                <div className="flex flex-wrap gap-1.5">
                                    <button
                                        type="button"
                                        onClick={() => handleClassClick('')}
                                        className={`rounded-lg px-2.5 py-1 text-xs font-bold transition-colors ${
                                            !activeClass
                                                ? 'bg-primary/10 text-primary'
                                                : 'text-muted-foreground hover:bg-muted'
                                        }`}
                                    >
                                        All
                                    </button>
                                    {classes.map((cls) => (
                                        <button
                                            key={cls.id}
                                            type="button"
                                            onClick={() => handleClassClick(cls.slug)}
                                            className={`rounded-lg px-2.5 py-1 text-xs font-bold transition-colors ${
                                                activeClass === cls.slug
                                                    ? 'bg-primary/10 text-primary'
                                                    : 'text-muted-foreground hover:bg-muted'
                                            }`}
                                        >
                                            {cls.name}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {hasActiveFilters && (
                                <button
                                    type="button"
                                    onClick={handleClearFilters}
                                    className="inline-flex items-center gap-1 text-xs font-semibold text-rose-500 hover:text-rose-600"
                                >
                                    <X className="size-3.5" />
                                    <span>Clear filters</span>
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Main Content Area */}
            <div className="mx-auto max-w-6xl px-4 py-8">
                {resources.data.length === 0 ? (
                    <EmptyState
                        title="No coloring sheets match these filters"
                        description="Try selecting a different theme or clearing your search keywords."
                        action={
                            <Button variant="outline" onClick={handleClearFilters}>
                                View all coloring pages
                            </Button>
                        }
                    />
                ) : (
                    <div className="space-y-8">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-bold text-slate-900 dark:text-white">
                                {activeTheme
                                    ? themes.find((t) => t.slug === activeTheme)?.name ?? 'Coloring Sheets'
                                    : 'All Printable Coloring Pages'}
                                <span className="ml-2 text-sm font-normal text-muted-foreground">
                                    ({resources.total} sheets available)
                                </span>
                            </h2>
                        </div>

                        {/* Grid of Coloring Cards */}
                        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {resources.data.map((resource) => (
                                <article
                                    key={resource.id}
                                    className="group flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-pink-300 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900 dark:hover:border-pink-800"
                                >
                                    <div>
                                        {/* Preview Thumbnail */}
                                        <Link
                                            href={resource.url ?? `/coloring/${resource.slug}`}
                                            className="relative block aspect-[4/3] w-full overflow-hidden rounded-2xl bg-gradient-to-tr from-pink-50 via-slate-50 to-purple-50 p-3 text-center dark:from-slate-800 dark:to-slate-850"
                                        >
                                            {resource.preview_image_url ? (
                                                <img
                                                    src={resource.preview_image_url}
                                                    alt={resource.title}
                                                    className="size-full object-contain transition-transform duration-300 group-hover:scale-105"
                                                    loading="lazy"
                                                />
                                            ) : (
                                                <div className="flex size-full flex-col items-center justify-center gap-2 border-2 border-dashed border-pink-200/80 rounded-xl p-4 text-center dark:border-slate-700">
                                                    <Palette className="size-10 text-pink-400" />
                                                    <span className="text-xs font-bold text-slate-600 dark:text-slate-300 line-clamp-2">
                                                        {resource.title}
                                                    </span>
                                                </div>
                                            )}

                                            <div className="absolute top-2.5 left-2.5 flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-0.5 text-[11px] font-bold text-pink-600 shadow-2xs backdrop-blur-xs dark:bg-slate-900/90 dark:text-pink-400">
                                                <Sparkles className="size-3" />
                                                <span>Free PDF</span>
                                            </div>

                                            {resource.page_count && resource.page_count > 1 && (
                                                <div className="absolute bottom-2.5 right-2.5 rounded-full bg-slate-900/80 px-2 py-0.5 text-[11px] font-bold text-white shadow-xs backdrop-blur-xs">
                                                    {resource.page_count} Pages
                                                </div>
                                            )}
                                        </Link>

                                        {/* Card Info */}
                                        <div className="mt-3.5 space-y-1.5">
                                            <div className="flex items-center gap-2 text-[11px] font-semibold text-muted-foreground">
                                                {resource.class_name && (
                                                    <Badge variant="outline" className="text-[10px] py-0 px-2">
                                                        {resource.class_name}
                                                    </Badge>
                                                )}
                                                {resource.subject_name && (
                                                    <span>• {resource.subject_name}</span>
                                                )}
                                            </div>

                                            <h3 className="line-clamp-1 text-base font-bold text-slate-900 transition-colors group-hover:text-pink-600 dark:text-white dark:group-hover:text-pink-400">
                                                <Link href={resource.url ?? `/coloring/${resource.slug}`}>
                                                    {resource.title}
                                                </Link>
                                            </h3>

                                            {resource.summary && (
                                                <p className="line-clamp-2 text-xs text-muted-foreground">
                                                    {resource.summary}
                                                </p>
                                            )}
                                        </div>
                                    </div>

                                    {/* Action Buttons */}
                                    <div className="mt-4 flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                        <Button
                                            asChild
                                            size="sm"
                                            className="w-full rounded-xl bg-pink-600 font-bold text-white hover:bg-pink-700 shadow-2xs"
                                        >
                                            <Link href={resource.url ?? `/coloring/${resource.slug}`}>
                                                <Download className="mr-1.5 size-3.5" />
                                                <span>Download</span>
                                            </Link>
                                        </Button>

                                        <Button
                                            asChild
                                            variant="outline"
                                            size="sm"
                                            className="rounded-xl border-slate-200 hover:bg-slate-50 dark:border-slate-700"
                                            title="View details & print"
                                        >
                                            <Link href={resource.url ?? `/coloring/${resource.slug}`}>
                                                <Printer className="size-3.5 text-slate-600 dark:text-slate-300" />
                                            </Link>
                                        </Button>
                                    </div>
                                </article>
                            ))}
                        </div>

                        {/* Pagination */}
                        <Pagination
                            currentPage={resources.current_page}
                            lastPage={resources.last_page}
                            hrefForPage={(page) => {
                                const params = new URLSearchParams();
                                if (activeTheme) params.set('theme', activeTheme);
                                if (activeClass) params.set('class', activeClass);
                                if (filters.q) params.set('q', filters.q);
                                params.set('page', String(page));
                                return `/coloring?${params.toString()}`;
                            }}
                        />
                    </div>
                )}

                {/* Cross-Promotion: Master Coloring Workbooks from Shop */}
                {featuredWorkbooks.length > 0 && (
                    <div className="mt-16 rounded-3xl border border-purple-200 bg-gradient-to-br from-purple-50/70 via-pink-50/40 to-amber-50/50 p-6 sm:p-8 dark:border-slate-800 dark:from-slate-900 dark:via-purple-950/20 dark:to-slate-950">
                        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div className="space-y-1">
                                <div className="inline-flex items-center gap-1.5 rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-700 dark:bg-purple-950 dark:text-purple-300">
                                    <ShoppingBag className="size-3.5" />
                                    <span>Complete Printed & Digital Collections</span>
                                </div>
                                <h3 className="text-xl font-bold text-slate-900 sm:text-2xl dark:text-white">
                                    Want 40+ Pages of Themed Fun?
                                </h3>
                                <p className="text-sm text-slate-600 dark:text-slate-300 max-w-xl">
                                    Explore our master learning workbooks and mega-bundles in the KiddoZen Shop.
                                    Designed by educators with step-by-step skill progression.
                                </p>
                            </div>

                            <Button asChild className="rounded-full bg-purple-600 font-bold text-white hover:bg-purple-700 shadow-md">
                                <Link href="/shop">
                                    Browse Shop Catalogue
                                </Link>
                            </Button>
                        </div>

                        <div className="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                            {featuredWorkbooks.map((book) => (
                                <Link
                                    key={book.id}
                                    href={book.url}
                                    className="group flex items-center gap-3.5 rounded-2xl border border-slate-200/80 bg-white/90 p-3.5 shadow-xs transition-all hover:border-purple-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900"
                                >
                                    {book.cover_image_url ? (
                                        <img
                                            src={book.cover_image_url}
                                            alt={book.name}
                                            className="size-16 rounded-xl object-cover shrink-0"
                                        />
                                    ) : (
                                        <div className="flex size-16 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950 dark:text-purple-300 font-bold text-xs text-center p-1">
                                            Workbook
                                        </div>
                                    )}
                                    <div className="truncate">
                                        <h4 className="line-clamp-1 text-sm font-bold text-slate-900 group-hover:text-purple-600 dark:text-white">
                                            {book.name}
                                        </h4>
                                        <p className="text-xs text-muted-foreground truncate">
                                            {book.class_name ?? 'Class 1–3'}
                                        </p>
                                        <div className="mt-1 flex items-center gap-1.5 font-bold text-xs text-purple-700 dark:text-purple-400">
                                            <span>{book.price ? book.price.formatted : book.regular_price.formatted}</span>
                                            {book.on_sale && (
                                                <span className="text-[11px] font-normal text-muted-foreground line-through">
                                                    {book.regular_price.formatted}
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
