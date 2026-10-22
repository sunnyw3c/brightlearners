import { Link } from '@inertiajs/react';
import { ArrowRight, BookOpen } from 'lucide-react';
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

interface PaginatedArticles {
    data: ArticleItem[];
    current_page: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
    total: number;
}

interface Props {
    category: CategoryItem;
    articles: PaginatedArticles;
    categories: CategoryItem[];
    breadcrumbs: Array<{ label: string; href?: string }>;
    seo: SeoProps;
}

export default function ParentHubCategory({
    category,
    articles,
    categories,
    breadcrumbs,
    seo,
}: Props) {
    return (
        <PublicLayout>
            <Seo seo={seo} />

            <div className="mx-auto max-w-6xl space-y-10 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Category Header */}
                <div className="space-y-4">
                    <div className="flex items-center gap-2">
                        <Link
                            href="/parents"
                            className="text-xs text-muted-foreground hover:text-foreground"
                        >
                            Parent Hub
                        </Link>
                        <span className="text-muted-foreground">/</span>
                        <Badge variant="secondary" className="text-xs">
                            Category
                        </Badge>
                    </div>
                    <h1 className="text-3xl font-extrabold tracking-tight sm:text-4xl">
                        {category.name}
                    </h1>
                    {category.description && (
                        <p className="max-w-3xl text-lg text-muted-foreground">
                            {category.description}
                        </p>
                    )}
                </div>

                {/* Category Chips Bar */}
                <div className="flex items-center gap-2 overflow-x-auto border-b pb-2">
                    <Link
                        href="/parents"
                        className="shrink-0 rounded-full px-3.5 py-1.5 text-xs font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                    >
                        All Categories
                    </Link>
                    {categories.map((cat) => {
                        const isActive = cat.id === category.id;
                        return (
                            <Link
                                key={cat.id}
                                href={`/parents/${cat.slug}`}
                                className={`shrink-0 rounded-full px-3.5 py-1.5 text-xs font-medium transition-colors ${
                                    isActive
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted/60 text-muted-foreground hover:bg-accent hover:text-foreground'
                                }`}
                            >
                                {cat.name}
                            </Link>
                        );
                    })}
                </div>

                {/* Articles List */}
                {articles.data.length === 0 ? (
                    <div className="rounded-2xl border border-dashed p-12 text-center text-muted-foreground">
                        <BookOpen className="mx-auto mb-3 size-12 opacity-40" />
                        <h3 className="text-lg font-semibold text-foreground">
                            No guides in this category yet
                        </h3>
                        <p className="mx-auto mt-1 max-w-md text-sm">
                            We are writing new guides for this area. In the
                            meantime, browse our other categories or home
                            learning worksheets.
                        </p>
                        <div className="mt-6">
                            <Link
                                href="/parents"
                                className="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                            >
                                Back to Parent Hub
                            </Link>
                        </div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {articles.data.map((article) => (
                            <Link
                                key={article.id}
                                href={`/blog/${article.slug}`}
                                className="group block rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            >
                                <Card className="flex h-full flex-col justify-between transition-all duration-200 group-hover:-translate-y-1 group-hover:border-primary/50 group-hover:shadow-md">
                                    <CardHeader>
                                        <CardTitle className="text-lg leading-snug transition-colors group-hover:text-primary">
                                            {article.title}
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent className="space-y-4">
                                        <CardDescription className="line-clamp-3 text-sm">
                                            {article.excerpt}
                                        </CardDescription>
                                        <div className="flex items-center justify-between border-t pt-3 text-xs text-muted-foreground">
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
            </div>
        </PublicLayout>
    );
}
