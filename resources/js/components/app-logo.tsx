import { KidDozenMascot } from '@/components/brand/kid-dozen-logo';

export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-8.5 items-center justify-center rounded-xl bg-amber-50 p-0.5 shadow-2xs dark:bg-amber-950/40">
                <KidDozenMascot size="sm" className="size-7" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-black text-slate-900 dark:text-white">
                    Kiddo<span className="text-[#2563EB]">Zen</span>
                </span>
                <span className="text-[10px] font-semibold text-muted-foreground">
                    Smart Learning
                </span>
            </div>
        </>
    );
}
