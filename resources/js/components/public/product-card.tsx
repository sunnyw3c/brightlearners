import { Link } from '@inertiajs/react';
import { BookOpen } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import type { ProductCardData } from '@/types/public';

export function ProductCard({ product }: { product: ProductCardData }) {
    return (
        <Link href={product.url}>
            <div className="flex h-full flex-col overflow-hidden rounded-2xl border bg-card shadow-sm transition-shadow hover:shadow-md">
                <div className="aspect-4/3 w-full overflow-hidden bg-linear-to-br from-violet-500/10 via-primary/10 to-highlight/10">
                    {product.cover_image_url ? (
                        <img
                            src={product.cover_image_url}
                            alt={product.name}
                            loading="lazy"
                            className="h-full w-full object-cover"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <BookOpen className="size-10 text-primary/50" />
                        </div>
                    )}
                </div>
                <div className="flex flex-1 flex-col gap-2 p-4">
                    <div className="flex items-center gap-2">
                        {product.on_sale && (
                            <Badge className="border-transparent bg-highlight text-highlight-foreground">
                                SALE
                            </Badge>
                        )}
                        {product.class_name && (
                            <span className="text-xs text-muted-foreground">
                                {product.class_name}
                            </span>
                        )}
                    </div>
                    <p className="text-base leading-snug font-semibold">
                        {product.name}
                    </p>
                    {product.short_description && (
                        <p className="line-clamp-2 text-sm text-muted-foreground">
                            {product.short_description}
                        </p>
                    )}
                    <div className="mt-auto flex items-baseline gap-2 pt-1">
                        <span className="text-lg font-bold text-warm">
                            {product.price?.formatted ??
                                product.regular_price.formatted}
                        </span>
                        {product.on_sale && (
                            <span className="text-sm text-muted-foreground line-through">
                                {product.regular_price.formatted}
                            </span>
                        )}
                    </div>
                </div>
            </div>
        </Link>
    );
}
