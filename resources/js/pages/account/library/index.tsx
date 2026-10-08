import { useState } from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { BookOpen, Download as DownloadIcon, AlertCircle, Sparkles, FileDown } from 'lucide-react';

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
    free: LibraryResourceProp[];
};

export default function LibraryIndex({ purchased, membership, free }: Props) {
    const [activeTab, setActiveTab] = useState<'purchased' | 'membership' | 'free'>('purchased');

    const currentList = activeTab === 'purchased' ? purchased : activeTab === 'membership' ? membership : free;

    return (
        <AppLayout>
            <Head title="My Learning Library — BrightLearners" />

            <div className="mx-auto max-w-5xl space-y-6 px-4 py-8">
                <header className="space-y-1">
                    <h1 className="text-3xl font-bold tracking-tight">My Learning Library</h1>
                    <p className="text-sm text-muted-foreground">
                        Access and download all your purchased worksheets, topic packs, and free materials.
                    </p>
                </header>

                {/* Tabs */}
                <div className="flex border-b border-border space-x-6 text-sm font-medium">
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
                        Membership (0)
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
                    <div className="rounded-2xl border bg-card p-12 text-center space-y-4">
                        <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-muted text-muted-foreground">
                            <FileDown className="w-6 h-6" />
                        </div>
                        <div className="space-y-1">
                            <h3 className="font-semibold text-base">No Resources Found</h3>
                            <p className="text-xs text-muted-foreground max-w-sm mx-auto">
                                {activeTab === 'purchased'
                                    ? "You haven't purchased any learning resources yet. Explore our shop to find topic packs and workbooks!"
                                    : activeTab === 'membership'
                                    ? 'Membership programmes and weekly releases will be available soon.'
                                    : 'You have not downloaded any free resources yet.'}
                            </p>
                        </div>
                        {activeTab === 'purchased' && (
                            <Button asChild className="mt-2" size="sm">
                                <a href="/shop">Browse Shop</a>
                            </Button>
                        )}
                    </div>
                ) : (
                    <div className="grid gap-4 md:grid-cols-2">
                        {currentList.map((item) => (
                            <div key={item.id} className="rounded-2xl border bg-card p-5 shadow-sm space-y-4 flex flex-col justify-between">
                                <div className="space-y-2">
                                    <div className="flex items-start justify-between gap-2">
                                        <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary capitalize">
                                            {item.type_label}
                                        </span>
                                        {item.current_version && (
                                            <span className="text-xs text-muted-foreground font-mono">
                                                v{item.current_version}
                                            </span>
                                        )}
                                    </div>

                                    <h2 className="font-bold text-base line-clamp-1">
                                        <a href={`/account/library/${item.slug}`} className="hover:underline">
                                            {item.title}
                                        </a>
                                    </h2>

                                    {item.summary && (
                                        <p className="text-xs text-muted-foreground line-clamp-2 leading-relaxed">
                                            {item.summary}
                                        </p>
                                    )}

                                    {item.has_correction_notice && (
                                        <div className="flex items-center gap-2 rounded-lg bg-amber-50 dark:bg-amber-950/40 p-2.5 text-xs text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50">
                                            <AlertCircle className="w-4 h-4 shrink-0" />
                                            <span>An updated version of this resource is available!</span>
                                        </div>
                                    )}
                                </div>

                                <div className="pt-2 border-t flex items-center justify-between gap-2 text-xs">
                                    <span className="text-muted-foreground">
                                        {item.granted_at ? `Purchased ${item.granted_at}` : item.downloaded_at ? `Downloaded ${item.downloaded_at}` : ''}
                                    </span>

                                    <div className="flex items-center gap-2">
                                        <Button asChild variant="outline" size="sm">
                                            <a href={`/account/library/${item.slug}`}>Details</a>
                                        </Button>
                                        <Button asChild size="sm">
                                            <a href={item.download_url} target="_blank" rel="noopener noreferrer">
                                                <DownloadIcon className="mr-1.5 h-3.5 w-3.5" /> Download
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
