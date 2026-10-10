import { Head, router } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { Button } from '@/components/ui/button';
import {
    CheckCircle2,
    Sparkles,
    ShieldCheck,
    Clock,
    BookOpen,
    ArrowRight,
    XCircle,
} from 'lucide-react';

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
};

export default function MembershipIndex({
    plan,
    product,
    is_active_member,
}: Props) {
    const handleJoin = () => {
        if (!product) return;
        router.post('/cart/items', { product_id: product.id });
    };

    return (
        <PublicLayout>
            <Head title="Weekly Learning Programme (90-Day Pilot) — BrightLearners">
                <meta
                    name="description"
                    content="Structured weekly learning worksheets and guided practice across Maths, English, and EVS for Class 1–3. Screen-free, curriculum-aligned, and delivered on schedule."
                />
            </Head>

            {/* Hero Section */}
            <section className="relative overflow-hidden border-b bg-gradient-to-b from-primary/5 via-background to-background py-16 sm:py-24">
                <div className="mx-auto max-w-5xl space-y-6 px-4 text-center sm:px-6 lg:px-8">
                    <div className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-3.5 py-1 text-xs font-semibold text-primary">
                        <Sparkles className="h-3.5 w-3.5" />
                        <span>Prepaid 90-Day Family Pilot</span>
                    </div>

                    <h1 className="text-4xl font-extrabold tracking-tight text-foreground sm:text-5xl">
                        Weekly Learning Routine for Class 1–3
                    </h1>

                    <p className="mx-auto max-w-2xl text-base leading-relaxed text-muted-foreground sm:text-lg">
                        Say goodbye to random internet worksheets. Get 4
                        carefully structured packs every month, complete with
                        parent teaching guidance and answer keys—designed for
                        calm, consistent 20-minute daily practice.
                    </p>

                    <div className="flex flex-col items-center justify-center gap-4 pt-4 sm:flex-row">
                        {is_active_member ? (
                            <Button
                                asChild
                                size="lg"
                                className="rounded-full px-8 text-base"
                            >
                                <a href="/account/weekly-learning">
                                    Go to My Weekly Learning{' '}
                                    <ArrowRight className="ml-2 h-4 w-4" />
                                </a>
                            </Button>
                        ) : product ? (
                            <Button
                                size="lg"
                                onClick={handleJoin}
                                className="rounded-full px-8 text-base shadow-lg hover:shadow-primary/20"
                            >
                                Join 90-Day Pilot for {product.price_formatted}
                            </Button>
                        ) : null}

                        <Button
                            asChild
                            variant="outline"
                            size="lg"
                            className="rounded-full px-6"
                        >
                            <a href="/membership/sample-month">
                                View Sample Month{' '}
                                <BookOpen className="ml-2 h-4 w-4" />
                            </a>
                        </Button>
                    </div>

                    <div className="flex items-center justify-center gap-6 pt-4 text-xs text-muted-foreground">
                        <span className="flex items-center gap-1.5">
                            <ShieldCheck className="h-4 w-4 text-emerald-600" />{' '}
                            One-time payment (no auto-renew)
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Clock className="h-4 w-4 text-emerald-600" /> 90
                            days access
                        </span>
                        <span className="flex items-center gap-1.5">
                            <CheckCircle2 className="h-4 w-4 text-emerald-600" />{' '}
                            15% storewide discount
                        </span>
                    </div>
                </div>
            </section>

            {/* How It Works (4 Steps from mockup) */}
            <section className="border-b bg-card/30 py-16 sm:py-20">
                <div className="mx-auto max-w-5xl space-y-12 px-4 sm:px-6 lg:px-8">
                    <div className="space-y-2 text-center">
                        <h2 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            How the Weekly Programme Works
                        </h2>
                        <p className="text-sm text-muted-foreground">
                            A clear, stress-free rhythm for parents and
                            children.
                        </p>
                    </div>

                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div className="relative space-y-3 rounded-2xl border bg-card p-6 shadow-sm">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-lg font-bold text-primary">
                                1
                            </div>
                            <h3 className="text-base font-semibold">
                                Select Learning Profile
                            </h3>
                            <p className="text-xs leading-relaxed text-muted-foreground">
                                Link your child's Class (Class 1, 2, or 3). The
                                programme automatically releases appropriate
                                packs.
                            </p>
                        </div>

                        <div className="relative space-y-3 rounded-2xl border bg-card p-6 shadow-sm">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-lg font-bold text-primary">
                                2
                            </div>
                            <h3 className="text-base font-semibold">
                                New Pack Every Friday
                            </h3>
                            <p className="text-xs leading-relaxed text-muted-foreground">
                                Fresh printable PDFs arrive on schedule every
                                week. Print for the weekend or upcoming week.
                            </p>
                        </div>

                        <div className="relative space-y-3 rounded-2xl border bg-card p-6 shadow-sm">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-lg font-bold text-primary">
                                3
                            </div>
                            <h3 className="text-base font-semibold">
                                Follow Parent Notes
                            </h3>
                            <p className="text-xs leading-relaxed text-muted-foreground">
                                Clear 2-minute parent teaching guidance
                                accompanies every sheet, showing exactly what
                                skill is being practiced.
                            </p>
                        </div>

                        <div className="relative space-y-3 rounded-2xl border bg-card p-6 shadow-sm">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-lg font-bold text-primary">
                                4
                            </div>
                            <h3 className="text-base font-semibold">
                                Track Consistency
                            </h3>
                            <p className="text-xs leading-relaxed text-muted-foreground">
                                Complete sheets with answer keys and celebrate
                                small, regular learning wins without screen
                                pressure.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Inclusions & Exclusions */}
            <section className="border-b py-16 sm:py-20">
                <div className="mx-auto max-w-5xl space-y-12 px-4 sm:px-6 lg:px-8">
                    <div className="space-y-2 text-center">
                        <h2 className="text-2xl font-bold tracking-tight sm:text-3xl">
                            What's Included in the 90-Day Pilot
                        </h2>
                        <p className="text-sm text-muted-foreground">
                            Complete transparency about what membership covers.
                        </p>
                    </div>

                    <div className="grid gap-8 md:grid-cols-2">
                        {/* What's Included */}
                        <div className="space-y-5 rounded-3xl border border-emerald-200 bg-emerald-50/30 p-8 dark:border-emerald-900/50 dark:bg-emerald-950/20">
                            <div className="flex items-center gap-2 text-lg font-bold text-emerald-700 dark:text-emerald-400">
                                <CheckCircle2 className="h-5 w-5" /> What You
                                Get
                            </div>
                            <ul className="space-y-3 text-sm text-muted-foreground">
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>
                                            12 Weekly Learning Packs:
                                        </strong>{' '}
                                        4 packs per month over 90 days for Class
                                        1, 2, or 3.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>Core Subjects Covered:</strong>{' '}
                                        Balanced mix of Mathematics, English
                                        Phonics/Grammar, and Environmental
                                        Studies (EVS).
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>
                                            Print-Friendly Variants:
                                        </strong>{' '}
                                        Full colour, low-ink printer-friendly
                                        copies, and answer keys.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>15% Storewide Discount:</strong>{' '}
                                        Active members automatically receive 15%
                                        off all standalone topic packs and
                                        workbooks.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" />
                                    <span>
                                        <strong>Permanent Ownership:</strong>{' '}
                                        All PDFs you download during your 90
                                        days remain yours forever.
                                    </span>
                                </li>
                            </ul>
                        </div>

                        {/* What's Excluded & Policy */}
                        <div className="space-y-5 rounded-3xl border border-muted bg-card p-8">
                            <div className="flex items-center gap-2 text-lg font-bold text-foreground">
                                <XCircle className="h-5 w-5 text-muted-foreground" />{' '}
                                Exclusions & Expiry Policy
                            </div>
                            <ul className="space-y-3 text-sm text-muted-foreground">
                                <li className="flex items-start gap-2.5">
                                    <XCircle className="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        <strong>Flagship E-books:</strong>{' '}
                                        Standalone flagship revision and
                                        activity ebooks are excluded from
                                        membership.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <XCircle className="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        <strong>No Auto-Renewal:</strong> You
                                        will never be automatically charged
                                        again. When 90 days conclude, membership
                                        gently expires.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <XCircle className="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        <strong>After Expiry:</strong> New
                                        weekly releases cease after day 90.
                                        Downloaded files stay on your computer
                                        or device.
                                    </span>
                                </li>
                                <li className="flex items-start gap-2.5">
                                    <XCircle className="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        <strong>
                                            One Membership per Family:
                                        </strong>{' '}
                                        Covers up to multiple child learning
                                        profiles in your parent account.
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {/* Pricing Card */}
            <section className="bg-muted/20 py-16 sm:py-24">
                <div className="mx-auto max-w-xl space-y-8 px-4 text-center">
                    <div className="space-y-6 rounded-3xl border bg-card p-8 shadow-lg sm:p-10">
                        <div className="space-y-2">
                            <span className="inline-block text-xs font-bold tracking-wider text-primary uppercase">
                                Launch Pilot Pricing
                            </span>
                            <h3 className="text-3xl font-extrabold">
                                {plan?.name ?? '90-Day Family Pilot'}
                            </h3>
                            <p className="text-sm text-muted-foreground">
                                3 Full Months of Structured Weekly Learning
                            </p>
                        </div>

                        <div className="py-4">
                            <span className="text-5xl font-black text-foreground">
                                {product?.price_formatted ?? '₹499'}
                            </span>
                            <span className="text-sm text-muted-foreground">
                                {' '}
                                / one-time prepaid
                            </span>
                        </div>

                        <div className="space-y-2 border-y py-4 text-xs text-muted-foreground">
                            <div>✓ 12 Weekly Curriculum Packs</div>
                            <div>✓ Class 1, Class 2, or Class 3 selection</div>
                            <div>✓ 15% discount on all store items</div>
                            <div>✓ No subscription recurring traps</div>
                        </div>

                        {is_active_member ? (
                            <div className="space-y-2">
                                <div className="rounded-xl bg-emerald-50 p-3 text-sm font-medium text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                                    You have an active membership!
                                </div>
                                <Button asChild className="w-full" size="lg">
                                    <a href="/account/weekly-learning">
                                        Access Weekly Learning
                                    </a>
                                </Button>
                            </div>
                        ) : product ? (
                            <Button
                                size="lg"
                                className="w-full text-base"
                                onClick={handleJoin}
                            >
                                Join Pilot Programme
                            </Button>
                        ) : null}
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
