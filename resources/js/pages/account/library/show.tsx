import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import {
    Download as DownloadIcon,
    AlertCircle,
    FileText,
    CheckCircle,
    ArrowLeft,
} from 'lucide-react';

type ResourceDetailProp = {
    id: number;
    title: string;
    slug: string;
    type: string;
    type_label: string;
    summary: string | null;
    description: string | null;
    learning_objective: string | null;
    difficulty: string | null;
    estimated_minutes: number | null;
    page_count: number | null;
    has_answer_key: boolean;
    low_ink_available: boolean;
    current_version: string | null;
    has_correction_notice: boolean;
    access_source: string;
    download_url: string;
};

type Props = {
    resource: ResourceDetailProp;
};

export default function LibraryShow({ resource }: Props) {
    return (
        <AppLayout>
            <Head title={`${resource.title} — My Library`} />

            <div className="mx-auto max-w-4xl space-y-6 px-4 py-8">
                <Button asChild variant="outline" size="sm">
                    <a href="/account/library">
                        <ArrowLeft className="mr-2 h-4 w-4" /> Back to My
                        Library
                    </a>
                </Button>

                <div className="space-y-6 rounded-3xl border bg-card p-8 shadow-sm">
                    <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                        <div className="space-y-2">
                            <div className="flex items-center gap-2">
                                <span className="inline-flex items-center rounded bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary capitalize">
                                    {resource.type_label}
                                </span>
                                {resource.current_version && (
                                    <span className="font-mono text-xs text-muted-foreground">
                                        Version {resource.current_version}
                                    </span>
                                )}
                            </div>
                            <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                                {resource.title}
                            </h1>
                            {resource.summary && (
                                <p className="text-sm text-muted-foreground">
                                    {resource.summary}
                                </p>
                            )}
                        </div>

                        <Button asChild size="lg" className="shrink-0">
                            <a
                                href={resource.download_url}
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <DownloadIcon className="mr-2 h-4 w-4" />{' '}
                                Download PDF
                            </a>
                        </Button>
                    </div>

                    {resource.has_correction_notice && (
                        <div className="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                            <AlertCircle className="h-5 w-5 shrink-0" />
                            <div>
                                <span className="font-semibold">
                                    Material Correction Published:
                                </span>{' '}
                                A newer version of this PDF is now available for
                                download with updated content.
                            </div>
                        </div>
                    )}

                    {/* Download Variants */}
                    <div className="space-y-4 rounded-2xl border bg-muted/40 p-6">
                        <h2 className="text-base font-semibold">
                            Available Download Formats
                        </h2>
                        <div className="grid gap-3 sm:grid-cols-3">
                            <a
                                href={`${resource.download_url}?variant=colour`}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="block space-y-1 rounded-xl border bg-card p-4 text-center transition-colors hover:border-primary"
                            >
                                <FileText className="mx-auto h-6 w-6 text-primary" />
                                <div className="text-sm font-semibold">
                                    Full Colour PDF
                                </div>
                                <div className="text-xs text-muted-foreground">
                                    Standard printable
                                </div>
                            </a>

                            {resource.low_ink_available && (
                                <a
                                    href={`${resource.download_url}?variant=low_ink`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="block space-y-1 rounded-xl border bg-card p-4 text-center transition-colors hover:border-primary"
                                >
                                    <FileText className="mx-auto h-6 w-6 text-muted-foreground" />
                                    <div className="text-sm font-semibold">
                                        Low-Ink Variant
                                    </div>
                                    <div className="text-xs text-muted-foreground">
                                        Printer friendly
                                    </div>
                                </a>
                            )}

                            {resource.has_answer_key && (
                                <a
                                    href={`${resource.download_url}?variant=answer_key`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="block space-y-1 rounded-xl border bg-card p-4 text-center transition-colors hover:border-primary"
                                >
                                    <CheckCircle className="mx-auto h-6 w-6 text-emerald-600" />
                                    <div className="text-sm font-semibold">
                                        Answer Key
                                    </div>
                                    <div className="text-xs text-muted-foreground">
                                        Parent guide
                                    </div>
                                </a>
                            )}
                        </div>
                    </div>

                    {/* Metadata & Details */}
                    <div className="grid gap-4 pt-2 text-sm text-muted-foreground sm:grid-cols-2">
                        {resource.page_count && (
                            <div>
                                Pages:{' '}
                                <span className="font-medium text-foreground">
                                    {resource.page_count} pages
                                </span>
                            </div>
                        )}
                        {resource.estimated_minutes && (
                            <div>
                                Estimated Time:{' '}
                                <span className="font-medium text-foreground">
                                    {resource.estimated_minutes} mins
                                </span>
                            </div>
                        )}
                        {resource.difficulty && (
                            <div>
                                Difficulty:{' '}
                                <span className="font-medium text-foreground capitalize">
                                    {resource.difficulty}
                                </span>
                            </div>
                        )}
                        <div>
                            Access Source:{' '}
                            <span className="font-medium text-foreground capitalize">
                                {resource.access_source}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
