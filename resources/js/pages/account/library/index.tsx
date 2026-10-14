import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Download as DownloadIcon, AlertCircle, FileDown } from 'lucide-react';

type LibraryResourceProp = {
    id: number;
    title: string;
    slug: string;
    type: string;
    type_label: string;
    summary: string | null;
    current_version: string | null;
    has_answer_key: boolean;
    low_ink_available: boolean;
    has_correction_notice?: boolean;
    granted_at?: string;
    downloaded_at?: string;
    download_url: string;
};

type Props = {
    purchased: LibraryResourceProp[];
    membership: LibraryResourceProp[];
    completed: LibraryResourceProp[];
    free: LibraryResourceProp[];
};

export default function LibraryIndex({
    purchased,
    membership,
    completed = [],
    free,
}: Props) {
    const [activeTab, setActiveTab] = useState<
        'purchased' | 'membership' | 'completed' | 'free'
    >('purchased');

    const currentList =
        activeTab === 'purchased'
            ? purchased
            : activeTab === 'membership'
              ? membership
              : activeTab === 'completed'
                ? completed
                : free;

    return (
        <AppLayout
            breadcrumbs={[{ title: 'Downloads', href: '/account/library' }]}
        >
            <Head title="My Learning Library — BrightLearners" />

            <div className="mx-auto max-w-5xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-3xl font-bold tracking-tight">
                        My Learning Library
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Access and download all your purchased worksheets,
                        weekly membership packs, and completed materials.
                    </p>
                </header>

                {/* Tabs */}
                <div className="flex space-x-6 border-b border-border text-sm font-medium">
                    <button
                        onClick={() => setActiveTab('purchased')}
                        className={`pb-3 transition-colors ${
                            activeTab === 'purchased'
                                ? 'border-b-2 border-primary font-semibold text-foreground'
                                : 'text-muted-foreground hover:text-foreground'
                        }`}
                    >
                        Purchased ({purchased.length})
                    </button>
                    <button
                        onClick={() => setActiveTab('membership')}
                        className={`pb-3 transition-colors ${
                            activeTab === 'membership'
                                ? 'border-b-2 border-primary font-semibold text-foreground'
                                : 'text-muted-foreground hover:text-foreground'
                        }`}
                    >
                        Membership ({membership.length})
                    </button>
                    <button
                        onClick={() => setActiveTab('completed')}
                        className={`pb-3 transition-colors ${
                            activeTab === 'completed'
                                ? 'border-b-2 border-primary font-semibold text-foreground'
                                : 'text-muted-foreground hover:text-foreground'
                        }`}
                    >
                        Completed ({completed.length})
                    </button>
                    <button
                        onClick={() => setActiveTab('free')}
                        className={`pb-3 transition-colors ${
                            activeTab === 'free'
                                ? 'border-b-2 border-primary font-semibold text-foreground'
                                : 'text-muted-foreground hover:text-foreground'
                        }`}
                    >
                        Free Downloads ({free.length})
                    </button>
                </div>

                {/* Resource List */}
                {currentList.length === 0 ? (
                    <div className="space-y-4 rounded-2xl border bg-card p-12 text-center">
                        <div className="inline-flex h-12 w-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
                            <FileDown className="h-6 w-6" />
                        </div>
                        <div className="space-y-1">
                            <h3 className="text-base font-semibold">
                                No Resources Found
                            </h3>
                            <p className="mx-auto max-w-sm text-xs text-muted-foreground">
                                {activeTab === 'purchased'
                                    ? "You haven't purchased any learning resources yet. Explore our shop to find topic packs and workbooks!"
                                    : activeTab === 'membership'
                                      ? 'No released membership materials available yet. Weekly packs drop as your programme advances.'
                                      : activeTab === 'completed'
                                        ? 'No completed activities yet. Mark activities complete from your parent dashboard to see them here!'
                                        : 'You have not downloaded any free resources yet.'}
                            </p>
                        </div>
                        {activeTab === 'purchased' && (
                            <Button asChild className="mt-2" size="sm">
                                <Link href="/shop">Browse Shop</Link>
                            </Button>
                        )}
                        {activeTab === 'membership' && (
                            <Button asChild className="mt-2" size="sm">
                                <Link href="/membership">
                                    Explore Membership
                                </Link>
                            </Button>
                        )}
                        {activeTab === 'completed' && (
                            <Button asChild className="mt-2" size="sm">
                                <Link href="/account/dashboard">
                                    Go to Dashboard
                                </Link>
                            </Button>
                        )}
                    </div>
                ) : (
                    <div className="grid gap-4 md:grid-cols-2">
                        {currentList.map((item) => (
                            <div
                                key={item.id}
                                className="flex flex-col justify-between space-y-4 rounded-2xl border bg-card p-5 shadow-sm"
                            >
                                <div className="space-y-2">
                                    <div className="flex items-start justify-between gap-2">
                                        <span className="inline-flex items-center rounded bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary capitalize">
                                            {item.type_label}
                                        </span>
                                        {item.current_version && (
                                            <span className="font-mono text-xs text-muted-foreground">
                                                v{item.current_version}
                                            </span>
                                        )}
                                    </div>

                                    <h2 className="line-clamp-1 text-base font-bold">
                                        <Link
                                            href={`/account/library/${item.slug}`}
                                            className="hover:underline"
                                        >
                                            {item.title}
                                        </Link>
                                    </h2>

                                    {item.summary && (
                                        <p className="line-clamp-2 text-xs leading-relaxed text-muted-foreground">
                                            {item.summary}
                                        </p>
                                    )}

                                    {item.has_correction_notice && (
                                        <div className="flex items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 p-2.5 text-xs text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                                            <AlertCircle className="h-4 w-4 shrink-0" />
                                            <span>
                                                An updated version of this
                                                resource is available!
                                            </span>
                                        </div>
                                    )}
                                </div>

                                <div className="flex items-center justify-between gap-2 border-t pt-2 text-xs">
                                    <span className="text-muted-foreground">
                                        {item.granted_at
                                            ? `Purchased ${item.granted_at}`
                                            : item.downloaded_at
                                              ? `Downloaded ${item.downloaded_at}`
                                              : ''}
                                    </span>

                                    <div className="flex items-center gap-2">
                                        <Button
                                            asChild
                                            variant="outline"
                                            size="sm"
                                        >
                                            <Link
                                                href={`/account/library/${item.slug}`}
                                            >
                                                Details
                                            </Link>
                                        </Button>
                                        <Button asChild size="sm">
                                            <a
                                                href={item.download_url}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                <DownloadIcon className="mr-1.5 h-3.5 w-3.5" />{' '}
                                                Download
                                            </a>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
