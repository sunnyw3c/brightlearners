import { Head } from '@inertiajs/react';

export interface SeoProps {
    title: string;
    description?: string | null;
    canonical?: string | null;
    noindex?: boolean;
    og_title?: string | null;
    og_description?: string | null;
    og_image?: string | null;
    og_type?: string | null;
    json_ld?: Record<string, unknown> | Array<Record<string, unknown>> | null;
    google_site_verification?: string | null;
}

export function Seo({ seo }: { seo: SeoProps }) {
    const siteTitle = 'BrightLearners';
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
