import { Link } from '@inertiajs/react';
import { ArrowRight, BookOpen, Calendar, CheckCircle2, ChevronRight, Sparkles, User } from 'lucide-react';
import { Seo, type SeoProps } from '@/components/seo';
import { PublicBreadcrumbs } from '@/components/public/breadcrumbs';
import { PublicLayout } from '@/layouts/public-layout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface ArticleData {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    body: string;
    published_at?: string;
    author?: { name: string } | null;
    category: {
        name: string;
        slug: string;
        url: string;
    };
    og_image_path?: string | null;
}

interface TryThisItem {
    type: string;
    title: string;
    summary: string;
    badge: string;
    url: string;
}

interface RelatedArticle {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    author?: { name: string } | null;
}

interface Props {
    article: ArticleData;
    tryThis: TryThisItem[];
    relatedArticles: RelatedArticle[];
    breadcrumbs: Array<{ label: string; href?: string }>;
    seo: SeoProps;
}

export default function ArticleShow({ article, tryThis, relatedArticles, breadcrumbs, seo }: Props) {
    return (
        <PublicLayout>
            <Seo seo={seo} />

            <article className="mx-auto max-w-4xl space-y-10 px-4 py-8">
                <PublicBreadcrumbs items={breadcrumbs} />

                {/* Article Header */}
                <header className="space-y-4">
                    <div className="flex items-center gap-2">
                        <Link
                            href={article.category.url}
                            className="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary hover:bg-primary/20 transition-colors"
                        >
                            {article.category.name}
                        </Link>
                    </div>

                    <h1 className="text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl leading-tight">
                        {article.title}
                    </h1>

                    {article.excerpt && (
                        <p className="text-lg text-muted-foreground leading-relaxed">
                            {article.excerpt}
                        </p>
                    )}

                    <div className="flex flex-wrap items-center gap-4 text-xs text-muted-foreground pt-4 border-t">
                        {article.author && (
                            <div className="flex items-center gap-1.5">
                                <User className="size-3.5 text-foreground/70" />
                                <span>{article.author.name}</span>
                            </div>
                        )}
                        {article.published_at && (
                            <div className="flex items-center gap-1.5">
                                <Calendar className="size-3.5 text-foreground/70" />
                                <span>Published {article.published_at}</span>
                            </div>
                        )}
                        <span className="text-muted-foreground/60">•</span>
                        <span>BrightLearners Educator Guide</span>
                    </div>
                </header>

                {/* Main Article Body (Rich Text sanitized HTML) */}
                <div
                    className="prose prose-slate dark:prose-invert max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-a:text-primary prose-a:underline hover:prose-a:text-primary/80 prose-img:rounded-2xl"
                    dangerouslySetInnerHTML={{ __html: article.body }}
                />

                {/* "Try This" Internal Linking Section (step 12.14) */}
                {tryThis.length > 0 && (
                    <section className="mt-12 rounded-2xl border border-primary/20 bg-linear-to-br from-primary/5 via-card to-highlight/5 p-6 sm:p-8 space-y-6">
                        <div className="flex items-center gap-2.5">
                            <div className="flex size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                                <Sparkles className="size-4" />
                            </div>
                            <div>
                                <h2 className="text-xl font-bold tracking-tight">Try This at Home</h2>
                                <p className="text-xs text-muted-foreground">Resources and practice packs aligned with this guide</p>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {tryThis.map((item, idx) => (
                                <a
                                    key={idx}
                                    href={item.url}
                                    className="group block rounded-xl border bg-card p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/50 hover:shadow-sm"
                                >
                                    <div className="flex items-center justify-between gap-2 mb-2">
                                        <Badge variant="secondary" className="text-xs font-medium">
                                            {item.badge}
                                        </Badge>
                                        <ArrowRight className="size-3.5 text-muted-foreground group-hover:text-primary group-hover:translate-x-1 transition-all" />
                                    </div>
                                    <h3 className="font-semibold text-foreground group-hover:text-primary transition-colors text-sm">
                                        {item.title}
                                    </h3>
                                    {item.summary && (
                                        <p className="text-xs text-muted-foreground mt-1 line-clamp-2">
                                            {item.summary}
                                        </p>
                                    )}
                                </a>
                            ))}
                        </div>
                    </section>
                )}

                {/* Related Articles */}
                {relatedArticles.length > 0 && (
                    <section className="space-y-6 pt-10 border-t">
                        <div>
                            <h2 className="text-2xl font-bold tracking-tight">More in {article.category.name}</h2>
                            <p className="text-sm text-muted-foreground">Continue reading related learning advice</p>
                        </div>

                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            {relatedArticles.map((rel) => (
                                <Link
                                    key={rel.id}
                                    href={`/blog/${rel.slug}`}
                                    className="group block rounded-xl border bg-card p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/50 hover:shadow-sm"
                                >
                                    <h3 className="font-semibold text-sm group-hover:text-primary transition-colors line-clamp-2">
                                        {rel.title}
                                    </h3>
                                    <p className="text-xs text-muted-foreground mt-2 line-clamp-2">
                                        {rel.excerpt}
                                    </p>
                                    <div className="mt-3 flex items-center text-xs font-medium text-primary">
                                        <span>Read</span>
                                        <ChevronRight className="ml-0.5 size-3" />
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </section>
                )}

                {/* Back Link */}
                <div className="pt-6">
                    <Link
                        href={article.category.url}
                        className="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-foreground transition-colors"
                    >
                        ← Back to {article.category.name}
                    </Link>
                </div>
            </article>
        </PublicLayout>
    );
}
