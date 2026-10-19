import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg
            {...props}
            viewBox="0 0 100 100"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <defs>
                <radialGradient
                    id="kdIconGrad"
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
                <linearGradient id="kdIconCap" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stopColor="#3B82F6" />
                    <stop offset="100%" stopColor="#1D4ED8" />
                </linearGradient>
            </defs>

            {/* Orbiting Dozen Dots */}
            <circle cx="50" cy="5" r="2.2" fill="#3B82F6" />
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

            {/* Mascot Head */}
            <circle
                cx="50"
                cy="52"
                r="40"
                fill="url(#kdIconGrad)"
                stroke="#F59E0B"
                strokeWidth="2.5"
            />

            {/* Rosy Cheeks */}
            <ellipse cx="28" cy="58" rx="6" ry="4" fill="#FF6B8B" opacity="0.65" />
            <ellipse cx="72" cy="58" rx="6" ry="4" fill="#FF6B8B" opacity="0.65" />

            {/* Big Friendly Eyes with Twinkles */}
            <ellipse cx="36" cy="48" rx="6.5" ry="8.5" fill="#0F172A" />
            <circle cx="38.5" cy="45" r="2.8" fill="#FFFFFF" />
            <circle cx="34" cy="51" r="1.3" fill="#FFFFFF" />

            <ellipse cx="64" cy="48" rx="6.5" ry="8.5" fill="#0F172A" />
            <circle cx="66.5" cy="45" r="2.8" fill="#FFFFFF" />
            <circle cx="62" cy="51" r="1.3" fill="#FFFFFF" />

            {/* Cheerful Smile */}
            <path
                d="M 40 58 Q 50 72 60 58"
                stroke="#0F172A"
                strokeWidth="3"
                strokeLinecap="round"
                fill="#C2410C"
            />
            <path d="M 45 64 Q 50 71 55 64" fill="#FF85A2" />

            {/* Scholar / Explorer Cap */}
            <polygon
                points="50,15 76,24 50,33 24,24"
                fill="url(#kdIconCap)"
                stroke="#1E40AF"
                strokeWidth="1.5"
            />
            <path d="M 37 28 C 37 36 63 36 63 28 Z" fill="#1D4ED8" />
            <circle cx="50" cy="24" r="2.5" fill="#FBBF24" />
            <path
                d="M 50 24 Q 66 26 70 36"
                stroke="#FBBF24"
                strokeWidth="2"
                strokeLinecap="round"
                fill="none"
            />
            <circle cx="70" cy="36" r="2" fill="#F59E0B" />
        </svg>
    );
}
