import { Link, router } from '@inertiajs/react';
import {
    ArrowRight,
    BookOpen,
    CheckCircle2,
    Clock,
    ShieldCheck,
    Sparkles,
    XCircle,
} from 'lucide-react';
import { Seo, type SeoProps } from '@/components/seo';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { Button } from '@/components/ui/button';
import PublicLayout from '@/layouts/public-layout';

type PlanProp = {
    code: string;
    name: string;
    price: number;
    price_formatted: string;
    duration_days: number;
    benefits: {
        member_discount_percent?: number;
        inclusions?: string;
    } | null;
};

type ProductProp = {
    id: number;
    name: string;
    slug: string;
    price: number;
    price_formatted: string;
};

type Props = {
    plan: PlanProp | null;
    product: ProductProp | null;
    is_active_member: boolean;
    seo: SeoProps;
};

export default function MembershipIndex({
    plan,
    product,
    is_active_member,
    seo,
}: Props) {
    const handleJoin = () => {
        if (!product) return;
        router.post('/cart/items', { product_id: product.id });
    };

    const breadcrumbs = [{ label: 'Home', href: '/' }, { label: 'Membership' }];

    const displayPrice = product?.price_formatted ?? '₹499';

    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-6xl space-y-12 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Hero / Pricing Section matching mockup screen 5 */}
                <div className="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
                    {/* Left Column: Title, Subtitle & Checkmarks */}
                    <div className="space-y-6 lg:col-span-7">
                        <div className="inline-flex items-center gap-2 rounded-full border border-sky-100 bg-sky-50 px-3.5 py-1 text-xs font-bold text-blue-600 dark:border-sky-900/50 dark:bg-sky-950/40 dark:text-sky-300">
                            <Sparkles className="size-3.5" />
                            <span>Prepaid 90-Day Programme</span>
                        </div>

                        <h1 className="text-4xl font-black tracking-tight text-slate-900 sm:text-5xl dark:text-white">
                            Weekly Learning Programme (90 Days)
                        </h1>

                        <p className="text-lg font-medium text-slate-600 sm:text-xl dark:text-slate-300">
                            Structured learning. Happy children. Confident
                            parents.
                        </p>

                        {/* 6 Benefits Checklist matching mockup */}
                        <div className="space-y-3.5 pt-2">
                            {[
                                'Weekly learning resources',
                                'Worksheets, activities and guides',
                                'Class 1 to 3 focus',
                                'Builds concepts step-by-step',
                                'Parent-friendly guidance',
                                'Accessible anytime, anywhere',
                            ].map((benefit, i) => (
                                <div
                                    key={i}
                                    className="flex items-center gap-3 text-base font-semibold text-slate-700 dark:text-slate-200"
                                >
                                    <span className="flex size-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                                        <CheckCircle2 className="size-4 fill-emerald-500 text-white" />
                                    </span>
                                    <span>{benefit}</span>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Right Column: Pricing Card matching mockup */}
                    <div className="lg:col-span-5">
                        <div className="relative mx-auto max-w-sm overflow-hidden rounded-[2.5rem] border-2 border-amber-300 bg-linear-to-b from-[#FFFDF7] via-[#FFF9ED] to-[#FFF4E0] p-6 text-center shadow-xl dark:border-amber-800/60 dark:from-slate-900 dark:via-slate-900/95 dark:to-slate-800">
                            {/* Artwork of boy and rocket on organic backdrop */}
                            <div className="relative mx-auto mb-5 h-52 w-52 overflow-hidden rounded-[2.5rem] bg-gradient-to-tr from-amber-300 via-orange-300 to-amber-100 p-1.5 shadow-md dark:from-amber-600 dark:via-orange-600 dark:to-amber-500">
                                <div className="h-full w-full overflow-hidden rounded-[2.2rem]">
                                    <img
                                        src="/images/membership-boy.jpg"
                                        alt="Learning Adventure"
                                        className="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                    />
                                </div>
                            </div>

                            <div className="space-y-1">
                                <span className="text-4xl font-black text-slate-900 sm:text-5xl dark:text-white">
                                    {displayPrice}
                                </span>
                                <p className="text-sm font-bold text-amber-900/80 dark:text-amber-200">
                                    for 90 Days
                                </p>
                            </div>

                            <div className="mt-6">
                                {is_active_member ? (
                                    <Button
                                        asChild
                                        size="lg"
                                        className="h-13 w-full rounded-full bg-[#2563EB] text-base font-bold text-white shadow-lg hover:bg-blue-700"
                                    >
                                        <Link href="/account/weekly-learning">
                                            Access My Programme{' '}
                                            <ArrowRight className="ml-2 size-4" />
                                        </Link>
                                    </Button>
                                ) : (
                                    <Button
                                        size="lg"
                                        onClick={handleJoin}
                                        className="h-13 w-full rounded-full bg-[#EF4444] text-base font-bold text-white shadow-lg shadow-red-500/25 transition-all hover:bg-red-600 hover:shadow-xl"
                                    >
                                        Join Now
                                    </Button>
                                )}
                            </div>

                            <p className="mt-3 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                                One-time payment · No automatic renewal
                            </p>
                        </div>
                    </div>
                </div>

                {/* "How It Works" Section (4 Steps matching mockup) */}
                <section className="space-y-8 border-t border-slate-200 py-8 dark:border-slate-800">
                    <div className="text-center">
                        <h2 className="text-3xl font-extrabold tracking-tight text-foreground">
                            How It Works
                        </h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            A clear, stress-free routine for parents and
                            children.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div className="flex flex-col items-center rounded-3xl border border-slate-200/80 bg-card p-6 text-center shadow-xs dark:border-slate-800">
                            <span className="flex size-12 items-center justify-center rounded-full bg-[#FBBF24] text-lg font-black text-amber-950 shadow-xs">
                                1
                            </span>
                            <h3 className="mt-4 text-base font-bold text-foreground">
                                Enrol
                            </h3>
                            <p className="mt-1 text-xs text-muted-foreground">
                                Join the 90-day programme and choose your
                                child's class.
                            </p>
                        </div>

                        <div className="flex flex-col items-center rounded-3xl border border-slate-200/80 bg-card p-6 text-center shadow-xs dark:border-slate-800">
                            <span className="flex size-12 items-center justify-center rounded-full bg-[#FBBF24] text-lg font-black text-amber-950 shadow-xs">
                                2
                            </span>
                            <h3 className="mt-4 text-base font-bold text-foreground">
                                Get Weekly
                            </h3>
                            <p className="mt-1 text-xs text-muted-foreground">
                                Receive structured printable resources every
                                week.
                            </p>
                        </div>

                        <div className="flex flex-col items-center rounded-3xl border border-slate-200/80 bg-card p-6 text-center shadow-xs dark:border-slate-800">
                            <span className="flex size-12 items-center justify-center rounded-full bg-[#FBBF24] text-lg font-black text-amber-950 shadow-xs">
                                3
                            </span>
                            <h3 className="mt-4 text-base font-bold text-foreground">
                                Learn Together
                            </h3>
                            <p className="mt-1 text-xs text-muted-foreground">
                                Fun and structured activities with 2-minute
                                parent teaching tips.
                            </p>
                        </div>

                        <div className="flex flex-col items-center rounded-3xl border border-slate-200/80 bg-card p-6 text-center shadow-xs dark:border-slate-800">
                            <span className="flex size-12 items-center justify-center rounded-full bg-[#FBBF24] text-lg font-black text-amber-950 shadow-xs">
                                4
                            </span>
                            <h3 className="mt-4 text-base font-bold text-foreground">
                                See Progress
                            </h3>
                            <p className="mt-1 text-xs text-muted-foreground">
                                Build real confidence day by day and celebrate
                                weekly wins.
                            </p>
                        </div>
                    </div>
                </section>

                {/* Inclusions & Transparency Section */}
                <section className="space-y-8 border-t border-slate-200 py-8 dark:border-slate-800">
                    <div className="text-center">
                        <h2 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            What's Included in the 90-Day Pilot
                        </h2>
                    </div>

                    <div className="grid gap-8 md:grid-cols-2">
                        <div className="space-y-5 rounded-3xl border border-emerald-200 bg-emerald-50/40 p-8 dark:border-emerald-900/50 dark:bg-emerald-950/20">
                            <div className="flex items-center gap-2 text-lg font-bold text-emerald-800 dark:text-emerald-400">
                                <CheckCircle2 className="size-5" /> What You Get
                            </div>
                            <ul className="space-y-3 text-sm text-slate-700 dark:text-slate-300">
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>
                                            12 Weekly Learning Packs:
                                        </strong>{' '}
                                        4 packs per month over 90 days for Class
                                        1, 2, or 3.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>Core Subjects Covered:</strong>{' '}
                                        Balanced mix of Mathematics, English
                                        Phonics/Grammar, and EVS.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>
                                            Print-Friendly Variants:
                                        </strong>{' '}
                                        Full colour, low-ink printer copies, and
                                        answer keys.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>15% Storewide Discount:</strong>{' '}
                                        Active members automatically receive 15%
                                        off all standalone topic packs and
                                        workbooks.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>Permanent Ownership:</strong>{' '}
                                        All PDFs you download during your 90
                                        days remain yours forever.
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div className="space-y-5 rounded-3xl border border-slate-200 bg-card p-8 dark:border-slate-800">
                            <div className="flex items-center gap-2 text-lg font-bold text-foreground">
                                <XCircle className="size-5 text-muted-foreground" />{' '}
                                Exclusions & Expiry Policy
                            </div>
                            <ul className="space-y-3 text-sm text-muted-foreground">
                                <li className="flex items-start gap-2.5">
                                    <XCircle className="mt-0.5 size-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        <strong>Flagship E-books:</strong>{' '}
                                        Standalone flagship revision and
                                        activity ebooks are excluded from
                                        membership.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <XCircle className="mt-0.5 size-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        <strong>No Auto-Renewal:</strong> You
                                        will never be automatically charged
                                        again. When 90 days conclude, membership
                                        gently expires.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <XCircle className="mt-0.5 size-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        <strong>After Expiry:</strong> New
                                        weekly releases cease after day 90.
                                        Downloaded files stay on your computer
                                        or device.
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </PublicLayout>
    );
}
