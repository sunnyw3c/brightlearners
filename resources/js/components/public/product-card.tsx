import { Link } from '@inertiajs/react';
import { ArrowRight, BookOpen } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import type { ProductCardData } from '@/types/public';

export function ProductCard({ product }: { product: ProductCardData }) {
    return (
        <Link href={product.url} className="group">
            <article className="flex h-full flex-col overflow-hidden rounded-3xl border border-border/70 bg-card shadow-[0_16px_45px_-32px_rgba(15,35,80,0.42)] transition-all duration-300 group-hover:-translate-y-1 group-hover:border-primary/20 group-hover:shadow-[0_24px_60px_-32px_rgba(15,35,80,0.45)]">
                <div className="relative aspect-4/3 w-full overflow-hidden bg-linear-to-br from-violet-50 via-sky-50 to-amber-50 p-4">
                    {product.on_sale && (
                        <Badge className="absolute top-4 left-4 z-10 border-2 border-white bg-highlight px-2.5 py-1 text-[0.65rem] font-black tracking-wider text-highlight-foreground shadow-sm">
                            SALE
                        </Badge>
                    )}
                    {product.cover_image_url ? (
                        <img
                            src={product.cover_image_url}
                            alt={product.name}
                            loading="lazy"
                            className="h-full w-full rounded-2xl object-contain drop-shadow-lg transition-transform duration-500 group-hover:scale-[1.03]"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center rounded-2xl border border-white/80 bg-white/75">
                            <BookOpen
                                className="size-12 text-primary/45"
                                strokeWidth={1.5}
                            />
                        </div>
                    )}
                </div>
                <div className="flex flex-1 flex-col gap-3 p-5">
                    <p className="text-[0.68rem] font-bold tracking-[0.08em] text-primary/75 uppercase">
                        {[product.class_name, product.type.replace(/_/g, ' ')]
                            .filter(Boolean)
                            .join(' · ')}
                    </p>
                    <h3 className="text-base leading-snug font-extrabold tracking-tight">
                        {product.name}
                    </h3>
                    {product.short_description && (
                        <p className="line-clamp-2 text-sm leading-5 text-muted-foreground">
                            {product.short_description}
                        </p>
                    )}
                    <div className="mt-auto flex items-end justify-between gap-3 border-t border-border/70 pt-4">
                        <div className="flex items-baseline gap-2">
                            <span className="text-xl font-black text-warm">
                                {product.price?.formatted ??
                                    product.regular_price.formatted}
                            </span>
                            {product.on_sale && (
                                <span className="text-sm text-muted-foreground line-through">
                                    {product.regular_price.formatted}
                                </span>
                            )}
                        </div>
                        <span className="flex size-8 shrink-0 items-center justify-center rounded-xl bg-primary/9 text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground">
                            <ArrowRight className="size-4" />
                        </span>
                    </div>
                </div>
            </article>
        </Link>
    );
}
