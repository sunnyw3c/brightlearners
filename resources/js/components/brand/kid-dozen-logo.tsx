import { useState } from 'react';
import { Link } from '@inertiajs/react';
import { home } from '@/routes';

interface KidDozenLogoProps {
    className?: string;
    iconClassName?: string;
    textClassName?: string;
    size?: 'sm' | 'md' | 'lg' | 'xl';
    showText?: boolean;
    showTagline?: boolean;
    showDomainBadge?: boolean;
    asLink?: boolean;
    href?: string;
    onClick?: () => void;
}

/**
 * KidDozenMascot: An interactive, charming mascot icon designed specifically for kids.
 * When hovered or clicked, it winks, bounces, and sparkles!
 */
export function KidDozenMascot({
    size = 'md',
    isWinking = false,
    className = '',
    onClick,
}: {
    size?: 'sm' | 'md' | 'lg' | 'xl';
    isWinking?: boolean;
    className?: string;
    onClick?: () => void;
}) {
    const sizeMap = {
        sm: 'size-7 sm:size-8',
        md: 'size-9 sm:size-10',
        lg: 'size-12 sm:size-14',
        xl: 'size-16 sm:size-20',
    };

    return (
        <span
            onClick={onClick}
            role="button"
            tabIndex={0}
            title="Click me for a wink! 😉"
            className={`group relative inline-flex shrink-0 select-none items-center justify-center transition-transform duration-300 hover:scale-110 active:scale-95 ${sizeMap[size]} ${className}`}
        >
            {/* Soft Ambient Mascot Glow */}
            <span
                aria-hidden="true"
                className="absolute inset-0 rounded-full bg-linear-to-tr from-amber-400 via-yellow-300 to-orange-400 opacity-70 blur-xs transition-opacity group-hover:opacity-100 dark:opacity-50"
            />

            {/* Mascot SVG Character */}
            <svg
                viewBox="0 0 100 100"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                className="relative h-full w-full drop-shadow-md transition-transform duration-300 group-hover:rotate-6"
            >
                <defs>
                    {/* Radial Sunshine Gradient */}
                    <radialGradient
                        id="kdFaceGrad"
                        cx="42%"
                        cy="38%"
                        r="60%"
                        fx="40%"
                        fy="35%"
                    >
                        <stop offset="0%" stopColor="#FFF275" />
                        <stop offset="65%" stopColor="#FFB800" />
                        <stop offset="100%" stopColor="#FF9500" />
                    </radialGradient>

                    {/* Cute Cap Gradient */}
                    <linearGradient id="kdCapGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stopColor="#3B82F6" />
                        <stop offset="100%" stopColor="#1D4ED8" />
                    </linearGradient>

                    {/* Cheerful Eye Sparkle */}
                    <filter id="kdGlow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="2" stdDeviation="2" floodColor="#B45309" floodOpacity="0.25" />
                    </filter>
                </defs>

                {/* Playful Orbiting Sparks (Representing KiddoZen's learning delights) */}
                <circle cx="50" cy="5" r="2.2" fill="#3B82F6" className="animate-pulse" />
                <circle cx="73" cy="12" r="2.2" fill="#EC4899" />
                <circle cx="89" cy="28" r="2.2" fill="#10B981" />
                <circle cx="95" cy="50" r="2.2" fill="#8B5CF6" />
                <circle cx="89" cy="72" r="2.2" fill="#F59E0B" />
                <circle cx="73" cy="88" r="2.2" fill="#06B6D4" />
                <circle cx="50" cy="95" r="2.2" fill="#EF4444" />
                <circle cx="27" cy="88" r="2.2" fill="#10B981" />
                <circle cx="11" cy="72" r="2.2" fill="#3B82F6" />
                <circle cx="5" cy="50" r="2.2" fill="#F59E0B" />
                <circle cx="11" cy="28" r="2.2" fill="#8B5CF6" />
                <circle cx="27" cy="12" r="2.2" fill="#EC4899" />

                {/* Mascot Main Friendly Head Body */}
                <circle
                    cx="50"
                    cy="52"
                    r="40"
                    fill="url(#kdFaceGrad)"
                    stroke="#F59E0B"
                    strokeWidth="2.5"
                    filter="url(#kdGlow)"
                />

                {/* Cute Rosy Cheeks */}
                <ellipse cx="28" cy="58" rx="6" ry="4" fill="#FF6B8B" opacity="0.65" />
                <ellipse cx="72" cy="58" rx="6" ry="4" fill="#FF6B8B" opacity="0.65" />

                {/* Left Eye: Big, Bright, Friendly Cartoon Eye */}
                <ellipse cx="36" cy="48" rx="6.5" ry="8.5" fill="#0F172A" />
                {/* Highlights / reflections that bring character to life */}
                <circle cx="38.5" cy="45" r="2.8" fill="#FFFFFF" />
                <circle cx="34" cy="51" r="1.3" fill="#FFFFFF" />

                {/* Right Eye: Either Winking (when hovered/clicked) or Big and Bright */}
                {isWinking ? (
                    /* Playful wink curve */
                    <path
                        d="M 58 48 Q 65 41 72 48"
                        stroke="#0F172A"
                        strokeWidth="3.5"
                        strokeLinecap="round"
                        fill="none"
                    />
                ) : (
                    /* Open sparkling eye */
                    <>
                        <ellipse cx="64" cy="48" rx="6.5" ry="8.5" fill="#0F172A" />
                        <circle cx="66.5" cy="45" r="2.8" fill="#FFFFFF" />
                        <circle cx="62" cy="51" r="1.3" fill="#FFFFFF" />
                    </>
                )}

                {/* Cute Cheerful Smile with Little Tongue */}
                <path
                    d="M 40 58 Q 50 72 60 58"
                    stroke="#0F172A"
                    strokeWidth="3"
                    strokeLinecap="round"
                    fill="#C2410C"
                />
                {/* Rosy tongue */}
                <path
                    d="M 45 64 Q 50 71 55 64"
                    fill="#FF85A2"
                />

                {/* Playful Scholar Cap / Explorer Hat perched cheerfully */}
                <g className="transition-transform duration-300 group-hover:-translate-y-1">
                    {/* Cap Diamond */}
                    <polygon
                        points="50,15 76,24 50,33 24,24"
                        fill="url(#kdCapGrad)"
                        stroke="#1E40AF"
                        strokeWidth="1.5"
                    />
                    {/* Cap Skull Under-cap */}
                    <path
                        d="M 37 28 C 37 36 63 36 63 28 Z"
                        fill="#1D4ED8"
                    />
                    {/* Golden Button on Top */}
                    <circle cx="50" cy="24" r="2.5" fill="#FBBF24" />
                    {/* Swinging Golden Tassel */}
                    <path
                        d="M 50 24 Q 66 26 70 36"
                        stroke="#FBBF24"
                        strokeWidth="2"
                        strokeLinecap="round"
                        fill="none"
                    />
                    <circle cx="70" cy="36" r="2" fill="#F59E0B" />
                </g>

                {/* Sparkle star over the corner */}
                <path
                    d="M 82 22 L 83.5 26 L 87.5 27.5 L 83.5 29 L 82 33 L 80.5 29 L 76.5 27.5 L 80.5 26 Z"
                    fill="#FBBF24"
                    className="transition-transform duration-300 group-hover:scale-125"
                />
            </svg>
        </span>
    );
}

/**
 * KiddoZenLogo: Complete Brand Wordmark + Interactive Mascot
 * Energetic, friendly, and visually stunning brand identity for KiddoZen.
 */
export function KiddoZenLogo({
    className = '',
    iconClassName = '',
    textClassName = '',
    size = 'md',
    showText = true,
    showTagline = false,
    asLink = true,
    href = home().url,
    onClick,
}: KidDozenLogoProps) {
    const [winking, setWinking] = useState(false);

    const handleMascotClick = () => {
        // Trigger a 700ms wink micro-interaction!
        setWinking(true);
        setTimeout(() => setWinking(false), 700);
        if (onClick) {
            onClick();
        }
    };

    const textSizes = {
        sm: 'text-base',
        md: 'text-lg sm:text-xl',
        lg: 'text-2xl sm:text-3xl',
        xl: 'text-3xl sm:text-4xl',
    };

    const logoContent = (
        <div
            className={`group inline-flex items-center gap-2.5 tracking-tight transition-all ${className}`}
            onMouseEnter={() => setWinking(true)}
            onMouseLeave={() => setWinking(false)}
        >
            {/* Interactive Kid Mascot */}
            <KidDozenMascot
                size={size}
                isWinking={winking}
                className={iconClassName}
                onClick={handleMascotClick}
            />

            {/* Stylized Brand Wordmark */}
            {showText && (
                <div className="flex flex-col text-left leading-none">
                    <div className="flex items-center gap-1.5">
                        <span
                            className={`font-black tracking-tight text-slate-900 transition-colors dark:text-white ${textSizes[size]} ${textClassName}`}
                        >
                            Kiddo
                            <span className="bg-linear-to-r from-[#2563EB] via-blue-600 to-indigo-600 bg-clip-text text-transparent dark:from-sky-400 dark:via-blue-400 dark:to-indigo-300">
                                Zen
                            </span>
                        </span>

                        {/* Little Playful Sparkle */}
                        <span
                            aria-hidden="true"
                            className="text-xs font-black text-amber-500 transition-transform duration-300 group-hover:scale-125 group-hover:rotate-12 dark:text-amber-400"
                        >
                            ✦
                        </span>
                    </div>

                    {showTagline && (
                        <span className="mt-0.5 text-[10px] font-bold text-muted-foreground sm:text-[11px]">
                            Fun & Smart Learning
                        </span>
                    )}
                </div>
            )}
        </div>
    );

    if (asLink) {
        return (
            <Link
                href={href}
                className="inline-flex shrink-0 items-center focus:outline-hidden"
                aria-label="KiddoZen Home"
            >
                {logoContent}
            </Link>
        );
    }

    return logoContent;
}

export const KidDozenLogo = KiddoZenLogo;
export const KiddoZenMascot = KidDozenMascot;
export default KiddoZenLogo;
