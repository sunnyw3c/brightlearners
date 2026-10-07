import { Lightbulb } from 'lucide-react';
import { cn } from '@/lib/utils';

export function BrandLogo({ className }: { className?: string }) {
    return (
        <span className={cn('inline-flex items-center gap-2', className)}>
            <span className="relative flex size-8 items-center justify-center rounded-xl bg-highlight text-highlight-foreground shadow-[0_8px_20px_-10px_rgba(234,179,8,0.8)]">
                <Lightbulb className="size-[1.125rem]" strokeWidth={2.4} />
                <span className="absolute -right-1 -bottom-1 size-2 rounded-full border-2 border-background bg-primary" />
            </span>
            <span className="text-base font-extrabold tracking-[-0.035em] text-foreground">
                Bright<span className="text-primary">Learners</span>
            </span>
        </span>
    );
}
