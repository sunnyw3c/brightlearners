import { Link } from '@inertiajs/react';
import {
    BookOpen,
    Compass,
    ArrowRight,
    Sparkles,
    FolderHeart,
} from 'lucide-react';
import { Seo, type SeoProps } from '@/components/seo';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { PublicLayout } from '@/layouts/public-layout';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface CategoryItem {
    id: number;
    name: string;
    slug: string;
    description: string;
    articles_count?: number;
}

interface ArticleItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    published_at: string;
    author?: { name: string } | null;
    category: { name: string; slug: string };
}

interface Props {
    categories: CategoryItem[];
    featuredArticles: ArticleItem[];
    breadcrumbs: Array<{ label: string; href?: string }>;
    seo: SeoProps;
}

export default function ParentHubIndex({
    categories,
    featuredArticles,
    breadcrumbs,
    seo,
}: Props) {
    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-6xl space-y-12 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Hero section */}
                <div className="relative overflow-hidden rounded-3xl border border-border/60 bg-linear-to-br from-primary/10 via-background to-highlight/10 p-8 sm:p-12">
                    <div className="max-w-2xl space-y-4">
                        <Badge className="gap-1.5 border-primary/30 bg-primary/20 px-3 py-1 text-xs text-primary">
                            <Sparkles className="size-3.5" />
                            Parent Hub
                        </Badge>
                        <h1 className="text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">
                            Practical Learning Advice & Support for Parents
                        </h1>
                        <p className="text-base text-muted-foreground sm:text-lg">
                            Simple routines, clear explanations for school
                            topics, and evidence-based guidance to help your
                            child thrive in Class 1 to 3.
                        </p>
                    </div>
                </div>

                {/* Browse by Category */}
                <section className="space-y-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold tracking-tight">
                                Explore by Topic
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Browse guides and strategies organized by
                                learning area
                            </p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {categories.map((cat) => (
                            <Link
                                key={cat.id}
                                href={`/parents/${cat.slug}`}
                                className="group block rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            >
                                <Card className="h-full transition-all duration-200 group-hover:-translate-y-1 group-hover:border-primary/50 group-hover:shadow-md">
                                    <CardHeader className="pb-3">
                                        <div className="flex items-center justify-between gap-2">
                                            <div className="flex size-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                                <FolderHeart className="size-5" />
                                            </div>
                                            {typeof cat.articles_count ===
                                                'number' && (
                                                <Badge
                                                    variant="secondary"
                                                    className="text-xs"
                                                >
                                                    {cat.articles_count}{' '}
                                                    {cat.articles_count === 1
                                                        ? 'guide'
                                                        : 'guides'}
                                                </Badge>
                                            )}
                                        </div>
                                        <CardTitle className="pt-2 text-lg transition-colors group-hover:text-primary">
                                            {cat.name}
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <CardDescription className="line-clamp-2 text-sm">
                                            {cat.description}
                                        </CardDescription>
                                        <div className="mt-4 flex items-center text-xs font-semibold text-primary">
                                            <span>Read guides</span>
                                            <ArrowRight className="ml-1 size-3.5 transition-transform group-hover:translate-x-1" />
                                        </div>
                                    </CardContent>
                                </Card>
                            </Link>
                        ))}
                    </div>
                </section>

                {/* Latest Guides */}
                <section className="space-y-6">
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight">
                            Featured Parent Guides
                        </h2>
                        <p className="text-sm text-muted-foreground">
                            Actionable tips for common primary school learning
                            moments
                        </p>
                    </div>

                    {featuredArticles.length === 0 ? (
                        <div className="rounded-2xl border border-dashed p-10 text-center text-muted-foreground">
                            <BookOpen className="mx-auto mb-3 size-10 opacity-40" />
                            <p className="font-medium">
                                Guides are being prepared by our educators.
                            </p>
                            <p className="mt-1 text-sm">
                                Check back soon for new articles and home
                                learning advice.
                            </p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {featuredArticles.map((article) => (
                                <Link
                                    key={article.id}
                                    href={`/blog/${article.slug}`}
                                    className="group block rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                                >
                                    <Card className="flex h-full flex-col justify-between transition-all duration-200 group-hover:-translate-y-1 group-hover:border-primary/50 group-hover:shadow-md">
                                        <CardHeader>
                                            <div className="mb-2 flex items-center justify-between gap-2">
                                                <Badge
                                                    variant="outline"
                                                    className="text-xs font-normal"
                                                >
                                                    {article.category.name}
                                                </Badge>
                                            </div>
                                            <CardTitle className="text-lg leading-snug transition-colors group-hover:text-primary">
                                                {article.title}
                                            </CardTitle>
                                        </CardHeader>
                                        <CardContent className="space-y-4">
                                            <CardDescription className="line-clamp-3 text-sm">
                                                {article.excerpt}
                                            </CardDescription>
                                            <div className="flex items-center justify-between border-t pt-2 text-xs text-muted-foreground">
                                                <span>
                                                    {article.author?.name ??
                                                        'KiddoZen Team'}
                                                </span>
                                                <div className="flex items-center font-medium text-primary transition-transform group-hover:translate-x-1">
                                                    <span>Read guide</span>
                                                    <ArrowRight className="ml-1 size-3" />
                                                </div>
                                            </div>
                                        </CardContent>
                                    </Card>
                                </Link>
                            ))}
                        </div>
                    )}
                </section>
            </div>
        </PublicLayout>
    );
}
