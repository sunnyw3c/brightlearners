import { useState } from 'react';
import { Link } from '@inertiajs/react';
import {
    CheckCircle2,
    Clock,
    Download,
    FileText,
    Heart,
    Layers,
    Palette,
    Printer,
    Share2,
    Sparkles,
    Star,
    Target,
} from 'lucide-react';
import { Seo } from '@/components/seo';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import type {
    BreadcrumbData,
    ProductCardData,
    ResourceCardData,
    SeoProps,
} from '@/types/public';

type ResourceDetail = {
    learning_objective: string | null;
    estimated_minutes: number | null;
    page_count: number | null;
    supplies: string | null;
    has_answer_key: boolean;
    low_ink_available: boolean;
    difficulty: string | null;
    previews: { url: string; width: number; height: number }[];
    download_url: string;
};

type Props = {
    resource: ResourceCardData;
    resourceDetail: ResourceDetail;
    breadcrumbs: BreadcrumbData[];
    related: ResourceCardData[];
    shopRecommendations: ProductCardData[];
    seo: SeoProps;
};

export default function ColoringShow({
    resource,
    resourceDetail,
    breadcrumbs,
    related,
    shopRecommendations,
    seo,
}: Props) {
    const [selectedPageIndex, setSelectedPageIndex] = useState(0);
    const [copied, setCopied] = useState(false);

    const previews = resourceDetail.previews;
    const currentPreview = previews[selectedPageIndex]?.url || resource.preview_image_url;

    const handlePrint = () => {
        if (currentPreview) {
            const printWindow = window.open('', '_blank');
            if (printWindow) {
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Print - ${resource.title}</title>
                        <style>
                            @page { size: A4 portrait; margin: 10mm; }
                            body { margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #fff; }
                            img { max-width: 100%; max-height: 95vh; object-fit: contain; }
                        </style>
                    </head>
                    <body>
                        <img src="${currentPreview}" onload="window.print();window.close();" />
                    </body>
                    </html>
                `);
                printWindow.document.close();
            }
        } else {
            window.print();
        }
    };

    const handleShare = () => {
        if (navigator.share) {
            navigator.share({
                title: resource.title,
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        }
    };

    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-6xl px-4 py-6 sm:py-10">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Top Header Card */}
                <div className="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div className="space-y-2">
                        <div className="flex flex-wrap items-center gap-2">
                            <Badge className="bg-pink-100 text-pink-700 hover:bg-pink-200 dark:bg-pink-950 dark:text-pink-300">
                                <Sparkles className="mr-1 size-3" />
                                100% Free Coloring PDF
                            </Badge>

                            {resource.class_name && (
                                <Badge variant="outline" className="border-slate-300 dark:border-slate-700">
                                    {resource.class_name}
                                </Badge>
                            )}

                            {resource.subject_name && (
                                <Badge variant="outline" className="border-slate-300 dark:border-slate-700">
                                    {resource.subject_name}
                                </Badge>
                            )}

                            {resourceDetail.low_ink_available && (
                                <span className="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                                    Low-Ink Optimized
                                </span>
                            )}
                        </div>

                        <h1 className="text-2xl font-black text-slate-900 sm:text-4xl dark:text-white">
                            {resource.title}
                        </h1>

                        {resource.summary && (
                            <p className="max-w-2xl text-sm text-slate-600 sm:text-base dark:text-slate-300">
                                {resource.summary}
                            </p>
                        )}
                    </div>

                    <div className="flex shrink-0 items-center gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={handleShare}
                            className="rounded-full border-slate-200 dark:border-slate-700"
                        >
                            <Share2 className="mr-1.5 size-3.5" />
                            <span>{copied ? 'Copied Link!' : 'Share'}</span>
                        </Button>
                    </div>
                </div>

                {/* Main Interactive Grid */}
                <div className="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-12">
                    {/* Left & Center: Multi-Page Preview & Primary Actions (7 cols) */}
                    <div className="space-y-6 lg:col-span-7">
                        <div className="overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            {/* Main Sheet Preview Display */}
                            <div className="relative flex aspect-[3/4] max-h-[620px] w-full items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100 p-4 sm:p-8 dark:from-slate-850 dark:to-slate-900">
                                {currentPreview ? (
                                    <img
                                        src={currentPreview}
                                        alt={`${resource.title} - Page ${selectedPageIndex + 1}`}
                                        className="size-full rounded-xl object-contain shadow-md drop-shadow-sm transition-transform duration-300 hover:scale-[1.02]"
                                    />
                                ) : (
                                    <div className="flex size-full flex-col items-center justify-center gap-4 rounded-2xl border-2 border-dashed border-pink-200 bg-white p-6 text-center dark:border-slate-700 dark:bg-slate-850">
                                        <Palette className="size-16 text-pink-400 animate-pulse" />
                                        <div className="space-y-1">
                                            <p className="text-base font-bold text-slate-800 dark:text-white">
                                                {resource.title}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                High-contrast vector line art ready for printing
                                            </p>
                                        </div>
                                    </div>
                                )}

                                <div className="absolute top-4 right-4 rounded-full bg-slate-900/75 px-3 py-1 text-xs font-bold text-white shadow-xs backdrop-blur-xs">
                                    Page {selectedPageIndex + 1} of {Math.max(previews.length, resourceDetail.page_count || 1)}
                                </div>
                            </div>

                            {/* Page Selection Thumbnails (if multi-page compilation) */}
                            {previews.length > 1 && (
                                <div className="border-t border-slate-200 bg-slate-50/70 p-3 dark:border-slate-800 dark:bg-slate-900/60">
                                    <p className="mb-2 text-xs font-bold uppercase tracking-wider text-muted-foreground">
                                        Preview Pages ({previews.length}):
                                    </p>
                                    <div className="flex gap-2.5 overflow-x-auto pb-1 scrollbar-none">
                                        {previews.map((preview, index) => (
                                            <button
                                                key={preview.url}
                                                type="button"
                                                onClick={() => setSelectedPageIndex(index)}
                                                className={`relative size-16 shrink-0 overflow-hidden rounded-xl border-2 transition-all ${
                                                    selectedPageIndex === index
                                                        ? 'border-pink-600 ring-2 ring-pink-300 scale-105'
                                                        : 'border-slate-200 opacity-70 hover:opacity-100 dark:border-slate-700'
                                                }`}
                                            >
                                                <img
                                                    src={preview.url}
                                                    alt={`Page ${index + 1}`}
                                                    className="size-full object-cover"
                                                />
                                                <span className="absolute bottom-0 inset-x-0 bg-slate-900/80 text-[10px] font-bold text-white text-center py-0.5">
                                                    #{index + 1}
                                                </span>
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Direct Action Bar */}
                            <div className="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between border-t border-slate-100 dark:border-slate-800">
                                <Button
                                    asChild
                                    size="lg"
                                    className="flex-1 rounded-2xl bg-gradient-to-r from-pink-600 to-purple-600 font-bold text-white shadow-md hover:from-pink-700 hover:to-purple-700"
                                >
                                    <a href={resourceDetail.download_url} download>
                                        <Download className="mr-2 size-5" />
                                        <span>Download Free Printable PDF</span>
                                    </a>
                                </Button>

                                <Button
                                    size="lg"
                                    variant="outline"
                                    onClick={handlePrint}
                                    className="rounded-2xl border-slate-300 font-bold hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800"
                                >
                                    <Printer className="mr-2 size-4 text-slate-600 dark:text-slate-300" />
                                    <span>Print Sheet</span>
                                </Button>
                            </div>
                        </div>

                        {/* Pedagogical & Child Development Highlights */}
                        <div className="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900 space-y-4">
                            <h3 className="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <Target className="size-5 text-pink-600" />
                                <span>Learning & Developmental Goals</span>
                            </h3>

                            <p className="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                {resourceDetail.learning_objective ??
                                    'Develops pincer grip strength, hand-eye coordination, border awareness, and visual focus.'}
                            </p>

                            <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                                <div className="rounded-2xl border border-pink-100 bg-pink-50/50 p-3.5 dark:border-pink-900/30 dark:bg-pink-950/20">
                                    <div className="text-xs font-bold text-pink-700 dark:text-pink-300">Fine Motor Grip</div>
                                    <div className="text-[11px] text-muted-foreground mt-0.5">Strengthens finger muscles for handwriting readiness.</div>
                                </div>
                                <div className="rounded-2xl border border-purple-100 bg-purple-50/50 p-3.5 dark:border-purple-900/30 dark:bg-purple-950/20">
                                    <div className="text-xs font-bold text-purple-700 dark:text-purple-300">Color Recognition</div>
                                    <div className="text-[11px] text-muted-foreground mt-0.5">Enhances visual discrimination and aesthetic joy.</div>
                                </div>
                                <div className="rounded-2xl border border-amber-100 bg-amber-50/50 p-3.5 dark:border-amber-900/30 dark:bg-amber-950/20">
                                    <div className="text-xs font-bold text-amber-700 dark:text-amber-300">Screen-Free Focus</div>
                                    <div className="text-[11px] text-muted-foreground mt-0.5">Promotes calm mindfulness and creative expression.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Sheet Specs & Printer Tips & Shop Upsell (5 cols) */}
                    <div className="space-y-6 lg:col-span-5">
                        {/* Quick Specs Card */}
                        <div className="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                            <h3 className="text-base font-bold text-slate-900 dark:text-white">
                                Sheet Specifications
                            </h3>

                            <dl className="mt-4 space-y-3 text-sm">
                                <div className="flex items-center justify-between border-b border-slate-100 pb-2.5 dark:border-slate-800">
                                    <dt className="text-muted-foreground">Format</dt>
                                    <dd className="font-semibold text-slate-900 dark:text-white">A4 Printable PDF</dd>
                                </div>
                                <div className="flex items-center justify-between border-b border-slate-100 pb-2.5 dark:border-slate-800">
                                    <dt className="text-muted-foreground">Pages</dt>
                                    <dd className="font-semibold text-slate-900 dark:text-white">
                                        {resourceDetail.page_count ?? 1} Page{resourceDetail.page_count && resourceDetail.page_count > 1 ? 's' : ''}
                                    </dd>
                                </div>
                                <div className="flex items-center justify-between border-b border-slate-100 pb-2.5 dark:border-slate-800">
                                    <dt className="text-muted-foreground">Duration</dt>
                                    <dd className="font-semibold text-slate-900 dark:text-white">
                                        {resourceDetail.estimated_minutes ?? 15} mins
                                    </dd>
                                </div>
                                <div className="flex items-center justify-between border-b border-slate-100 pb-2.5 dark:border-slate-800">
                                    <dt className="text-muted-foreground">Recommended Supplies</dt>
                                    <dd className="font-semibold text-slate-900 dark:text-white text-right">
                                        {resourceDetail.supplies}
                                    </dd>
                                </div>
                                <div className="flex items-center justify-between">
                                    <dt className="text-muted-foreground">Licence</dt>
                                    <dd className="font-semibold text-slate-900 dark:text-white">Free Household / Classroom</dd>
                                </div>
                            </dl>
                        </div>

                        {/* Best Printing Advice Card */}
                        <div className="rounded-3xl border border-blue-200 bg-blue-50/60 p-5 dark:border-slate-800 dark:bg-blue-950/20">
                            <h4 className="text-sm font-bold text-blue-900 dark:text-blue-300 flex items-center gap-1.5">
                                <Printer className="size-4 text-blue-600" />
                                <span>Parent & Teacher Print Tips</span>
                            </h4>
                            <ul className="mt-2.5 space-y-2 text-xs text-blue-800/90 dark:text-blue-300/80">
                                <li className="flex items-start gap-1.5">
                                    <CheckCircle2 className="size-3.5 text-blue-600 shrink-0 mt-0.5" />
                                    <span>Select <strong>"Fit to Printable Area"</strong> in your printer settings.</span>
                                </li>
                                <li className="flex items-start gap-1.5">
                                    <CheckCircle2 className="size-3.5 text-blue-600 shrink-0 mt-0.5" />
                                    <span>Standard 75–80 GSM paper works great with wax crayons or colored pencils.</span>
                                </li>
                                <li className="flex items-start gap-1.5">
                                    <CheckCircle2 className="size-3.5 text-blue-600 shrink-0 mt-0.5" />
                                    <span>If your child loves watercolor or sketch pens, 100+ GSM paper prevents bleed-through.</span>
                                </li>
                            </ul>
                        </div>

                        {/* Curated Shop Recommendations */}
                        {shopRecommendations.length > 0 && (
                            <div className="rounded-3xl border border-purple-200 bg-purple-50/50 p-5 dark:border-slate-800 dark:bg-purple-950/20 space-y-3">
                                <div className="flex items-center justify-between">
                                    <h4 className="text-sm font-bold text-purple-950 dark:text-purple-200">
                                        Complete Master Workbooks
                                    </h4>
                                    <Link href="/shop" className="text-xs font-bold text-purple-600 hover:text-purple-700">
                                        View all
                                    </Link>
                                </div>

                                <div className="space-y-3">
                                    {shopRecommendations.map((product) => (
                                        <Link
                                            key={product.id}
                                            href={product.url}
                                            className="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-3 shadow-2xs transition-all hover:border-purple-300 hover:shadow-xs dark:border-slate-800 dark:bg-slate-900"
                                        >
                                            {product.cover_image_url ? (
                                                <img
                                                    src={product.cover_image_url}
                                                    alt={product.name}
                                                    className="size-14 rounded-xl object-cover shrink-0"
                                                />
                                            ) : (
                                                <div className="flex size-14 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-[10px] font-bold text-purple-700 text-center p-1">
                                                    Workbook
                                                </div>
                                            )}
                                            <div className="truncate">
                                                <div className="line-clamp-1 text-xs font-bold text-slate-900 dark:text-white">
                                                    {product.name}
                                                </div>
                                                <div className="text-[11px] text-muted-foreground truncate">
                                                    {product.class_name ?? 'Class 1–3'}
                                                </div>
                                                <div className="mt-0.5 font-bold text-xs text-purple-700 dark:text-purple-400">
                                                    {product.price ? product.price.formatted : product.regular_price.formatted}
                                                </div>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Related Coloring Sheets */}
                {related.length > 0 && (
                    <div className="mt-16 space-y-6">
                        <div className="flex items-center justify-between">
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white">
                                More Free Coloring Sheets You'll Love
                            </h3>
                            <Link href="/coloring" className="text-sm font-bold text-pink-600 hover:text-pink-700">
                                Explore all coloring pages →
                            </Link>
                        </div>

                        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            {related.map((item) => (
                                <Link
                                    key={item.id}
                                    href={item.url ?? `/coloring/${item.slug}`}
                                    className="group overflow-hidden rounded-3xl border border-slate-200/80 bg-white p-3.5 shadow-xs transition-all hover:-translate-y-1 hover:border-pink-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900"
                                >
                                    <div className="relative aspect-[4/3] overflow-hidden rounded-2xl bg-pink-50/50 p-2 text-center dark:bg-slate-800">
                                        {item.preview_image_url ? (
                                            <img
                                                src={item.preview_image_url}
                                                alt={item.title}
                                                className="size-full object-contain transition-transform group-hover:scale-105"
                                            />
                                        ) : (
                                            <div className="flex size-full items-center justify-center">
                                                <Palette className="size-8 text-pink-300" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="mt-3 space-y-1">
                                        <h4 className="line-clamp-1 text-sm font-bold text-slate-900 group-hover:text-pink-600 dark:text-white">
                                            {item.title}
                                        </h4>
                                        <p className="line-clamp-1 text-xs text-muted-foreground">
                                            {item.class_name ?? 'Foundational'} • Free PDF
                                        </p>
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
