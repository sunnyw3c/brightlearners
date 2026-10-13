import { useState } from 'react';
import { router } from '@inertiajs/react';
import {
    CheckCircle2,
    Download,
    FileCheck,
    HelpCircle,
    Minus,
    Plus,
    ShieldCheck,
    ShoppingCart,
    Star,
    Zap,
} from 'lucide-react';
import { Seo } from '@/components/seo';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { ProductCard } from '@/components/public/product-card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';
import type {
    BreadcrumbData,
    ProductCardData,
    SeoProps,
} from '@/types/public';

type Inclusion = {
    title: string;
    type: string;
    page_count: number | null;
};

type ProductDetail = {
    description: string | null;
    total_pages: number;
    has_answer_key: boolean;
    language: string[];
    licence_type: string[];
    inclusions: Inclusion[];
    member_discount_eligible: boolean;
    cover_image_url: string | null;
    previews: { url: string; width: number; height: number }[];
};

type Props = {
    product: ProductCardData;
    productDetail: ProductDetail;
    related: ProductCardData[];
    breadcrumbs: BreadcrumbData[];
    seo: SeoProps;
};

const trustItems = [
    {
        icon: Download,
        title: 'Instant Download',
        desc: 'After Purchase',
    },
    {
        icon: ShieldCheck,
        title: 'Secure Payment',
        desc: 'Razorpay Verified',
    },
    {
        icon: HelpCircle,
        title: '30-Day Support',
        desc: "We're here to help",
    },
    {
        icon: FileCheck,
        title: 'High Quality PDF',
        desc: 'Print-friendly format',
    },
];

const whatsInsideTopics = [
    'Numbers and Place Value',
    'Addition and Subtraction',
    'Multiplication Basics',
    'Shapes and Patterns',
    'Measurement & Units',
    'Time and Calendar',
    'Data Handling & Pictographs',
    'Mixed Practice & Revision Tests',
];

export default function ShopShow({
    product,
    productDetail,
    related,
    breadcrumbs,
    seo,
}: Props) {
    const [quantity, setQuantity] = useState(1);
    const [activeTab, setActiveTab] = useState<'description' | 'whats_inside' | 'sample_pages' | 'reviews'>('whats_inside');
    const [isAdding, setIsAdding] = useState(false);

    const handleAddToCart = () => {
        setIsAdding(true);
        router.post(
            '/cart/items',
            { product_id: product.id },
            {
                preserveScroll: true,
                onFinish: () => setIsAdding(false),
            },
        );
    };

    const handleBuyNow = () => {
        router.post(
            '/cart/items',
            { product_id: product.id },
            {
                onSuccess: () => {
                    router.visit('/checkout');
                },
            },
        );
    };

    const coverImage =
        productDetail.cover_image_url ||
        product.cover_image_url ||
        '/images/maths-workbook-3d.jpg';

    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-6xl space-y-12 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Main Product Showcase matching mockup */}
                <div className="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
                    {/* Left: 3D Workbook Image */}
                    <div className="lg:col-span-5">
                        <div className="group relative mx-auto max-w-sm overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-linear-to-b from-amber-50/50 via-white to-sky-50/40 p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                            <div className="relative aspect-square w-full">
                                <img
                                    src={coverImage}
                                    alt={product.name}
                                    className="h-full w-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-105"
                                />
                            </div>
                        </div>
                    </div>

                    {/* Right: Product Info & Actions */}
                    <div className="space-y-6 lg:col-span-7">
                        <div>
                            <div className="mb-2 flex items-center gap-2">
                                <Badge className="bg-amber-100 text-amber-800 hover:bg-amber-100 dark:bg-amber-950 dark:text-amber-300">
                                    {product.class_name ?? 'Class 2'}
                                </Badge>
                                <span className="text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                    Workbook
                                </span>
                            </div>

                            <h1 className="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                                {product.name}
                            </h1>

                            {/* Ratings Row */}
                            <div className="mt-3 flex items-center gap-2 text-sm text-amber-500 font-bold">
                                <div className="flex">
                                    {[...Array(5)].map((_, i) => (
                                        <Star
                                            key={i}
                                            className={`size-4 ${
                                                i < 5
                                                    ? 'fill-amber-400 stroke-amber-500'
                                                    : 'fill-amber-200 stroke-amber-400'
                                            }`}
                                        />
                                    ))}
                                </div>
                                <span>4.8</span>
                                <span className="text-xs font-normal text-muted-foreground">
                                    (96 reviews)
                                </span>
                            </div>
                        </div>

                        {/* Price */}
                        <div className="flex items-baseline gap-3">
                            <span className="text-4xl font-black text-[#EF4444]">
                                {product.price?.formatted ??
                                    product.regular_price.formatted}
                            </span>
                            {product.on_sale && (
                                <span className="text-lg text-muted-foreground line-through">
                                    {product.regular_price.formatted}
                                </span>
                            )}
                        </div>

                        {/* Short Description */}
                        <p className="text-base text-slate-600 leading-relaxed dark:text-slate-300">
                            {product.short_description ??
                                'A complete practice workbook covering all key Class 2 maths concepts with step-by-step exercises and answers.'}
                        </p>

                        {/* Feature Bullet Points matching mockup */}
                        <div className="space-y-2.5">
                            {[
                                '100+ Practice Questions',
                                'Concept-based Learning',
                                'Answer Key Included',
                                'Printable PDF (High Quality)',
                                'Aligned to School Curriculum',
                            ].map((bullet, idx) => (
                                <div key={idx} className="flex items-center gap-2.5 text-sm font-medium text-slate-700 dark:text-slate-200">
                                    <CheckCircle2 className="size-4.5 shrink-0 text-emerald-500" />
                                    <span>{bullet}</span>
                                </div>
                            ))}
                        </div>

                        {/* Quantity Stepper & Buttons Row matching mockup */}
                        <div className="space-y-3 pt-2">
                            <div className="flex flex-wrap items-center gap-4">
                                {/* Quantity Stepper */}
                                <div className="flex items-center rounded-full border border-slate-200 bg-white px-2 py-1 shadow-xs dark:border-slate-800 dark:bg-slate-900">
                                    <button
                                        type="button"
                                        onClick={() => setQuantity((q) => Math.max(1, q - 1))}
                                        className="flex size-8 items-center justify-center rounded-full text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800"
                                    >
                                        <Minus className="size-3.5" />
                                    </button>
                                    <span className="w-8 text-center text-sm font-bold">
                                        {quantity}
                                    </span>
                                    <button
                                        type="button"
                                        onClick={() => setQuantity((q) => q + 1)}
                                        className="flex size-8 items-center justify-center rounded-full text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800"
                                    >
                                        <Plus className="size-3.5" />
                                    </button>
                                </div>

                                <Button
                                    size="lg"
                                    onClick={handleAddToCart}
                                    disabled={isAdding}
                                    className="h-12 flex-1 rounded-full bg-[#2563EB] px-8 text-base font-bold text-white shadow-md shadow-blue-500/25 hover:bg-blue-700 sm:flex-none"
                                >
                                    <ShoppingCart className="mr-2 size-4.5" />
                                    {isAdding ? 'Adding...' : 'Add to Cart'}
                                </Button>

                                <Button
                                    size="lg"
                                    variant="outline"
                                    onClick={handleBuyNow}
                                    className="h-12 flex-1 rounded-full border-blue-200 bg-blue-50 text-base font-bold text-blue-700 hover:bg-blue-100 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-300 sm:flex-none"
                                >
                                    <Zap className="mr-1.5 size-4" />
                                    Buy Now
                                </Button>
                            </div>
                        </div>

                        {/* 4 Trust Badges matching mockup */}
                        <div className="grid grid-cols-2 gap-3 pt-4 sm:grid-cols-4 border-t border-slate-100 dark:border-slate-800">
                            {trustItems.map((item, idx) => {
                                const IconComponent = item.icon;
                                return (
                                    <div
                                        key={idx}
                                        className="flex flex-col items-center rounded-2xl border border-slate-100 bg-slate-50/50 p-3 text-center dark:border-slate-800 dark:bg-slate-900/50"
                                    >
                                        <span className="flex size-9 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                                            <IconComponent className="size-4.5" />
                                        </span>
                                        <p className="mt-2 text-xs font-bold text-foreground">
                                            {item.title}
                                        </p>
                                        <p className="text-[11px] text-muted-foreground">
                                            {item.desc}
                                        </p>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>

                {/* Tabs Section matching mockup */}
                <div className="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                    <div className="flex items-center gap-3 border-b border-slate-200 pb-2 dark:border-slate-800">
                        <button
                            type="button"
                            onClick={() => setActiveTab('description')}
                            className={`rounded-full px-5 py-2 text-sm font-bold transition-all ${
                                activeTab === 'description'
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'text-slate-600 hover:text-foreground dark:text-slate-400'
                            }`}
                        >
                            Description
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('whats_inside')}
                            className={`rounded-full px-5 py-2 text-sm font-bold transition-all ${
                                activeTab === 'whats_inside'
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'text-slate-600 hover:text-foreground dark:text-slate-400'
                            }`}
                        >
                            What's Inside
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('sample_pages')}
                            className={`rounded-full px-5 py-2 text-sm font-bold transition-all ${
                                activeTab === 'sample_pages'
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'text-slate-600 hover:text-foreground dark:text-slate-400'
                            }`}
                        >
                            Sample Pages
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('reviews')}
                            className={`rounded-full px-5 py-2 text-sm font-bold transition-all ${
                                activeTab === 'reviews'
                                    ? 'bg-primary text-white shadow-sm'
                                    : 'text-slate-600 hover:text-foreground dark:text-slate-400'
                            }`}
                        >
                            Reviews
                        </button>
                    </div>

                    {/* Tab Body */}
                    {activeTab === 'whats_inside' && (
                        <div className="rounded-3xl border border-slate-200/80 bg-card p-8 shadow-xs dark:border-slate-800">
                            <div className="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                                <div className="space-y-4 flex-1">
                                    <h3 className="text-xl font-bold tracking-tight text-foreground">
                                        What's Inside
                                    </h3>
                                    <div className="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                                        {whatsInsideTopics.map((topic, i) => (
                                            <div key={i} className="flex items-center gap-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                <CheckCircle2 className="size-4.5 text-emerald-500 shrink-0" />
                                                <span>{topic}</span>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="shrink-0 flex justify-center">
                                    <img
                                        src="/images/class-2-boy.png"
                                        alt="Happy learning"
                                        className="h-36 w-36 object-contain drop-shadow-sm"
                                    />
                                </div>
                            </div>
                        </div>
                    )}

                    {activeTab === 'description' && (
                        <div className="rounded-3xl border border-slate-200/80 bg-card p-8 space-y-4 shadow-xs dark:border-slate-800">
                            <h3 className="text-xl font-bold tracking-tight text-foreground">
                                Detailed Curriculum Description
                            </h3>
                            <p className="text-slate-600 leading-relaxed dark:text-slate-300">
                                {productDetail.description ??
                                    'This comprehensive Class 2 Maths Workbook gives children the daily confidence they need. Every topic is laid out in a step-by-step format with clean visual examples and progressive challenges.'}
                            </p>
                        </div>
                    )}

                    {activeTab === 'sample_pages' && (
                        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xs dark:border-slate-800">
                                <img
                                    src="/images/worksheet-addition.jpg"
                                    alt="Sample page 1"
                                    className="h-auto w-full rounded-xl object-contain"
                                />
                            </div>
                            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xs dark:border-slate-800">
                                <img
                                    src="/images/worksheet-place-value.jpg"
                                    alt="Sample page 2"
                                    className="h-auto w-full rounded-xl object-contain"
                                />
                            </div>
                            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xs dark:border-slate-800">
                                <img
                                    src="/images/worksheet-time-calendar.jpg"
                                    alt="Sample page 3"
                                    className="h-auto w-full rounded-xl object-contain"
                                />
                            </div>
                        </div>
                    )}

                    {activeTab === 'reviews' && (
                        <div className="rounded-3xl border border-slate-200/80 bg-card p-8 space-y-4 shadow-xs dark:border-slate-800">
                            <div className="flex items-center gap-3">
                                <span className="text-3xl font-extrabold text-foreground">
                                    4.8
                                </span>
                                <div className="flex text-amber-500">
                                    {[...Array(5)].map((_, i) => (
                                        <Star key={i} className="size-5 fill-amber-400 stroke-amber-500" />
                                    ))}
                                </div>
                                <span className="text-sm text-muted-foreground">
                                    Based on 96 parent reviews
                                </span>
                            </div>
                        </div>
                    )}
                </div>

                {/* Related Products */}
                {related.length > 0 && (
                    <section className="space-y-6 pt-6 pb-12">
                        <h2 className="text-2xl font-bold tracking-tight text-foreground">
                            You May Also Like
                        </h2>
                        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {related.map((item) => (
                                <ProductCard key={item.id} product={item} />
                            ))}
                        </div>
                    </section>
                )}
            </div>
        </PublicLayout>
    );
}
