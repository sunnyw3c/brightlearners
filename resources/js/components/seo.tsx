import { Head } from '@inertiajs/react';
import type { SeoProps } from '@/types/public';

export type { SeoProps };

export function Seo({ seo }: { seo: SeoProps }) {
    const siteTitle = 'KidDozen';
    const title = seo.title;
    const description = seo.description ?? '';
    const canonical = seo.canonical ?? '';
    const ogTitle = seo.og_title || title;
    const ogDesc = seo.og_description || description;
    const ogImage = seo.og_image || '/images/og-default.png';
    const ogType = seo.og_type || 'website';
    const robots = seo.noindex ? 'noindex, nofollow' : 'index, follow';

    return (
        <Head title={title}>
            {description && <meta name="description" content={description} />}
            {canonical && <link rel="canonical" href={canonical} />}
            <meta name="robots" content={robots} />

            {/* Open Graph */}
            <meta property="og:site_name" content={siteTitle} />
            <meta property="og:title" content={ogTitle} />
            {ogDesc && <meta property="og:description" content={ogDesc} />}
            {canonical && <meta property="og:url" content={canonical} />}
            <meta property="og:type" content={ogType} />
            <meta property="og:image" content={ogImage} />

            {/* Twitter Card */}
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:title" content={ogTitle} />
            {ogDesc && <meta name="twitter:description" content={ogDesc} />}
            <meta name="twitter:image" content={ogImage} />

            {/* Search Console verification (step 12.13) */}
            {seo.google_site_verification && (
                <meta
                    name="google-site-verification"
                    content={seo.google_site_verification}
                />
            )}

            {/* Structured Data JSON-LD (steps 12.7, 12.8, 12.9) */}
            {seo.json_ld && (
                <script
                    type="application/ld+json"
                    dangerouslySetInnerHTML={{
                        __html: JSON.stringify(seo.json_ld),
                    }}
                />
            )}
        </Head>
    );
}

export default Seo;
