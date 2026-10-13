import { useState } from 'react';
import {
    BookOpen,
    CheckCircle2,
    Clock,
    Download,
    FileText,
    GraduationCap,
    Heart,
    Layers,
    Share2,
    Sparkles,
    Star,
    Target,
} from 'lucide-react';
import { Seo } from '@/components/seo';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { PreviewCarousel } from '@/components/public/preview-carousel';
import { ResourceCard } from '@/components/public/resource-card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import type {
    BreadcrumbData,
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
    related: ResourceCardData[];
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

function getWorksheetImage(resource: ResourceCardData): string {
    const text = (
        (resource.title || '') +
        ' ' +
        (resource.slug || '') +
        ' ' +
        (resource.subject_name || '')
    ).toLowerCase();

    if (text.includes('place') || text.includes('value')) {
        return '/images/worksheet-place-value.jpg';
    }
    if (text.includes('time') || text.includes('calendar') || text.includes('clock')) {
        return '/images/worksheet-time-calendar.jpg';
    }
    if (
        text.includes('describing') ||
        text.includes('word') ||
        text.includes('english') ||
        text.includes('grammar')
    ) {
        return '/images/worksheet-describing-words.jpg';
    }
    return '/images/worksheet-addition.jpg';
}

export default function FreeResourceShow({
    resource,
    resourceDetail,
    related,
    breadcrumbs,
    seo,
}: Props) {
    const [activeTab, setActiveTab] = useState<'preview' | 'answer_key' | 'related'>('preview');
    const [isSaved, setIsSaved] = useState(false);
    const [copied, setCopied] = useState(false);

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

    const mainImageUrl =
        resource.preview_image_url && !resource.preview_image_url.includes('/storage/previews/')
            ? resource.preview_image_url
            : getWorksheetImage(resource);

    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-6xl space-y-10 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Top Section: Split Preview & Details */}
                <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12">
                    {/* Left: Preview Carousel */}
                    <div className="lg:col-span-6">
                        <PreviewCarousel
                            previews={resourceDetail.previews}
                            title={resource.title}
                            fallbackUrl={mainImageUrl}
                        />
                    </div>

                    {/* Right: Specifications & CTA */}
                    <div className="space-y-6 lg:col-span-6">
                        <div>
                            <h1 className="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl lg:text-4xl dark:text-white">
                                {resource.title}
                            </h1>

                            <div className="mt-3 flex flex-wrap items-center gap-3">
                                <div className="flex items-center gap-1.5 text-amber-500 font-bold text-sm">
                                    <div className="flex">
                                        {[...Array(5)].map((_, i) => (
                                            <Star
                                                key={i}
                                                className={`size-4 ${
                                                    i < 4
                                                        ? 'fill-amber-400 stroke-amber-500'
                                                        : 'fill-amber-200 stroke-amber-400'
                                                }`}
                                            />
                                        ))}
                                    </div>
                                    <span>4.8</span>
                                    <span className="text-xs font-normal text-muted-foreground">
                                        (124 reviews)
                                    </span>
                                </div>

                                <span className="inline-flex items-center rounded-full bg-emerald-500 px-3 py-0.5 text-xs font-bold text-white shadow-xs">
                                    Free Resource
                                </span>
                            </div>
                        </div>

                        {resource.summary && (
                            <p className="text-base leading-relaxed text-slate-600 dark:text-slate-300">
                                {resource.summary}
                            </p>
                        )}

                        {/* Meta Specifications List matching mockup */}
                        <div className="rounded-3xl border border-slate-200/80 bg-slate-50/50 p-6 shadow-2xs dark:border-slate-800 dark:bg-slate-900/50">
                            <div className="grid grid-cols-1 gap-3.5 sm:grid-cols-2 text-sm">
                                <div className="flex items-center gap-3">
                                    <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                                        <GraduationCap className="size-4" />
                                    </span>
                                    <div>
                                        <span className="text-xs text-muted-foreground">Class</span>
                                        <p className="font-bold text-foreground">
                                            {resource.class_name ?? 'Class 2'}
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-center gap-3">
                                    <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950 dark:text-purple-400">
                                        <BookOpen className="size-4" />
                                    </span>
                                    <div>
                                        <span className="text-xs text-muted-foreground">Subject</span>
                                        <p className="font-bold text-foreground">
                                            {resource.subject_name ?? 'Maths'}
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-center gap-3">
                                    <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                                        <Target className="size-4" />
                                    </span>
                                    <div>
                                        <span className="text-xs text-muted-foreground">Topic</span>
                                        <p className="font-bold text-foreground">
                                            Addition & Subtraction
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-center gap-3">
                                    <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-400">
                                        <FileText className="size-4" />
                                    </span>
                                    <div>
                                        <span className="text-xs text-muted-foreground">Format</span>
                                        <p className="font-bold text-foreground">
                                            PDF (Printable)
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-center gap-3">
                                    <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-600 dark:bg-teal-950 dark:text-teal-400">
                                        <Layers className="size-4" />
                                    </span>
                                    <div>
                                        <span className="text-xs text-muted-foreground">Pages</span>
                                        <p className="font-bold text-foreground">
                                            {resourceDetail.page_count ?? 4}
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-center gap-3">
                                    <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                                        <CheckCircle2 className="size-4" />
                                    </span>
                                    <div>
                                        <span className="text-xs text-muted-foreground">Includes</span>
                                        <p className="font-bold text-foreground">
                                            Worksheet + Answer Key
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Action Buttons Row */}
                        <div className="flex flex-wrap items-center gap-3 pt-2">
                            <Button
                                asChild
                                size="lg"
                                className="h-13 rounded-full bg-[#2563EB] px-8 text-base font-bold text-white shadow-lg shadow-blue-500/25 hover:bg-blue-700 transition-all flex-1 sm:flex-none"
                            >
                                <a href={resourceDetail.download_url}>
                                    <Download className="mr-2 size-5" />
                                    Download Free PDF
                                </a>
                            </Button>

                            <button
                                type="button"
                                onClick={() => setIsSaved(!isSaved)}
                                aria-label="Save resource"
                                className={`flex size-13 items-center justify-center rounded-full border border-slate-200 bg-white transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 ${
                                    isSaved ? 'text-rose-500 border-rose-200 bg-rose-50' : 'text-slate-600'
                                }`}
                            >
                                <Heart
                                    className={`size-5 ${
                                        isSaved ? 'fill-rose-500 text-rose-500' : ''
                                    }`}
                                />
                            </button>

                            <button
                                type="button"
                                onClick={handleShare}
                                aria-label="Share resource"
                                className="flex size-13 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900"
                            >
                                <Share2 className="size-5" />
                            </button>
                            {copied && (
                                <span className="text-xs font-semibold text-emerald-600">
                                    Link copied!
                                </span>
                            )}
                        </div>
                    </div>
                </div>

                {/* Bottom Section: Tabs & You May Also Like matching mockup */}
                <div className="space-y-6 pt-6">
                    {/* Tab Navigation */}
                    <div className="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-2">
                        <button
                            type="button"
                            onClick={() => setActiveTab('preview')}
                            className={`rounded-full px-5 py-2 text-sm font-bold transition-all ${
                                activeTab === 'preview'
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'text-slate-600 hover:text-foreground dark:text-slate-400'
                            }`}
                        >
                            Preview
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('answer_key')}
                            className={`rounded-full px-5 py-2 text-sm font-bold transition-all ${
                                activeTab === 'answer_key'
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'text-slate-600 hover:text-foreground dark:text-slate-400'
                            }`}
                        >
                            Answer Key
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('related')}
                            className={`rounded-full px-5 py-2 text-sm font-bold transition-all ${
                                activeTab === 'related'
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'text-slate-600 hover:text-foreground dark:text-slate-400'
                            }`}
                        >
                            Related Resources
                        </button>
                    </div>

                    {/* Tab Body & Sidebar */}
                    <div className="grid grid-cols-1 gap-10 lg:grid-cols-12">
                        {/* Tab Content Display */}
                        <div className="lg:col-span-8">
                            {activeTab === 'preview' && (
                                <div className="space-y-4">
                                    <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-md dark:border-slate-800 dark:bg-slate-900">
                                        <img
                                            src={mainImageUrl}
                                            onError={(e) => {
                                                e.currentTarget.src = getWorksheetImage(resource);
                                            }}
                                            alt={`${resource.title} full sheet preview`}
                                            className="mx-auto max-h-[850px] w-auto rounded-xl object-contain shadow-xs"
                                        />
                                    </div>
                                </div>
                            )}

                            {activeTab === 'answer_key' && (
                                <div className="rounded-3xl border border-emerald-100 bg-emerald-50/50 p-8 text-center dark:border-emerald-950 dark:bg-emerald-950/20">
                                    <CheckCircle2 className="mx-auto size-12 text-emerald-600" />
                                    <h3 className="mt-3 text-lg font-bold text-emerald-950 dark:text-emerald-300">
                                        Complete Answer Key Included
                                    </h3>
                                    <p className="mt-1 text-sm text-emerald-800 dark:text-emerald-400">
                                        All questions come with step-by-step verified solutions on the final page of this printable download.
                                    </p>
                                </div>
                            )}

                            {activeTab === 'related' && (
                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    {related.map((item) => (
                                        <ResourceCard key={item.id} resource={item} />
                                    ))}
                                </div>
                            )}
                        </div>

                        {/* You May Also Like Sidebar matching mockup */}
                        <div className="space-y-4 lg:col-span-4">
                            <h3 className="text-lg font-bold tracking-tight text-foreground">
                                You May Also Like
                            </h3>
                            <div className="space-y-3">
                                {related.length > 0 ? (
                                    related.slice(0, 3).map((item) => (
                                        <div
                                            key={item.id}
                                            className="group flex items-center gap-3.5 rounded-2xl border border-slate-200/80 bg-card p-3 shadow-2xs transition-all hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800"
                                        >
                                            <div className="size-16 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                <img
                                                    src={getWorksheetImage(item)}
                                                    alt={item.title}
                                                    className="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <h4 className="line-clamp-1 text-sm font-bold text-foreground group-hover:text-primary transition-colors">
                                                    {item.title}
                                                </h4>
                                                <div className="mt-1 flex items-center gap-2 text-xs">
                                                    <span className="flex items-center gap-1 font-semibold text-amber-500">
                                                        <Star className="size-3 fill-amber-400 stroke-amber-500" />
                                                        4.7
                                                    </span>
                                                    <span className="text-muted-foreground">·</span>
                                                    <span className="font-semibold text-emerald-600">
                                                        Free
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <>
                                        <div className="group flex items-center gap-3.5 rounded-2xl border border-slate-200/80 bg-card p-3 shadow-2xs hover:shadow-md dark:border-slate-800">
                                            <div className="size-16 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                <img
                                                    src="/images/worksheet-place-value.jpg"
                                                    alt="Subtraction Practice"
                                                    className="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <h4 className="line-clamp-1 text-sm font-bold text-foreground">
                                                    Subtraction Practice (Class 2)
                                                </h4>
                                                <div className="mt-1 flex items-center gap-2 text-xs">
                                                    <span className="flex items-center gap-1 font-semibold text-amber-500">
                                                        <Star className="size-3 fill-amber-400 stroke-amber-500" />
                                                        4.7
                                                    </span>
                                                    <span className="text-muted-foreground">·</span>
                                                    <span className="font-semibold text-emerald-600">
                                                        Free
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="group flex items-center gap-3.5 rounded-2xl border border-slate-200/80 bg-card p-3 shadow-2xs hover:shadow-md dark:border-slate-800">
                                            <div className="size-16 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                <img
                                                    src="/images/worksheet-time-calendar.jpg"
                                                    alt="Word Problems"
                                                    className="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <h4 className="line-clamp-1 text-sm font-bold text-foreground">
                                                    Word Problems - Addition
                                                </h4>
                                                <div className="mt-1 flex items-center gap-2 text-xs">
                                                    <span className="flex items-center gap-1 font-semibold text-amber-500">
                                                        <Star className="size-3 fill-amber-400 stroke-amber-500" />
                                                        4.8
                                                    </span>
                                                    <span className="text-muted-foreground">·</span>
                                                    <span className="font-semibold text-emerald-600">
                                                        Free
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="group flex items-center gap-3.5 rounded-2xl border border-slate-200/80 bg-card p-3 shadow-2xs hover:shadow-md dark:border-slate-800">
                                            <div className="size-16 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                <img
                                                    src="/images/worksheet-describing-words.jpg"
                                                    alt="Mixed Practice"
                                                    className="h-full w-full object-cover"
                                                />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <h4 className="line-clamp-1 text-sm font-bold text-foreground">
                                                    Mixed Practice Worksheet
                                                </h4>
                                                <div className="mt-1 flex items-center gap-2 text-xs">
                                                    <span className="flex items-center gap-1 font-semibold text-amber-500">
                                                        <Star className="size-3 fill-amber-400 stroke-amber-500" />
                                                        4.6
                                                    </span>
                                                    <span className="text-muted-foreground">·</span>
                                                    <span className="font-semibold text-emerald-600">
                                                        Free
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
