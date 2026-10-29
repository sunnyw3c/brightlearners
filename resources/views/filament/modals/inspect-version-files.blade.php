@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Number;

    $disk = Storage::disk('resources');
    $mainExists = !empty($record->file_path) && $disk->exists($record->file_path);
    $mainSize = $mainExists ? Number::fileSize($disk->size($record->file_path)) : 'Missing on disk';

    $answerExists = !empty($record->answer_file_path) && $disk->exists($record->answer_file_path);
    $answerSize = $answerExists ? Number::fileSize($disk->size($record->answer_file_path)) : null;

    $lowInkExists = !empty($record->low_ink_path) && $disk->exists($record->low_ink_path);
    $lowInkSize = $lowInkExists ? Number::fileSize($disk->size($record->low_ink_path)) : null;
@endphp

<div class="space-y-6 text-sm text-gray-700 dark:text-gray-200">
    {{-- Summary Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-gray-50 dark:bg-gray-800/60 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
        <div>
            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold block">Version</span>
            <span class="font-bold text-base text-primary-600 dark:text-primary-400">v{{ $record->version }}</span>
        </div>
        <div>
            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold block">Preview Status</span>
            <span class="inline-flex items-center px-2 py-0.5 mt-0.5 rounded text-xs font-medium {{ $record->preview_status->value === 'ready' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                {{ ucfirst($record->preview_status->value ?? 'pending') }}
            </span>
        </div>
        <div>
            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold block">Current Active</span>
            <span class="font-medium text-sm {{ $record->is_current ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500' }}">
                {{ $record->is_current ? '✓ Yes (Live)' : 'No' }}
            </span>
        </div>
        <div>
            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold block">Uploaded Date</span>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $record->created_at?->format('M d, Y H:i') ?? '—' }}</span>
        </div>
    </div>

    {{-- File 1: Main Worksheet PDF --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-xs">
        <div class="flex items-start justify-between gap-3 mb-2.5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-xs">
                    PDF
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white text-base">Main Worksheet / Activity File</h4>
                    <span class="text-xs text-gray-400">Primary customer printable document</span>
                </div>
            </div>
            @if($mainExists && !empty($mainUrl))
                <a href="{{ $mainUrl }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Open PDF
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs bg-gray-50 dark:bg-gray-900/40 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
            <div>
                <span class="text-gray-400 block">Storage Path:</span>
                <code class="text-gray-800 dark:text-gray-200 break-all">{{ $record->file_path }}</code>
            </div>
            <div>
                <span class="text-gray-400 block">Status & Size:</span>
                <span class="font-medium {{ $mainExists ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600' }}">
                    {{ $mainExists ? '✓ Stored on private disk (' . $mainSize . ')' : '⚠ File missing on storage' }}
                </span>
            </div>
            @if(!empty($record->checksum))
                <div class="sm:col-span-2">
                    <span class="text-gray-400 block">SHA-256 Checksum:</span>
                    <code class="text-gray-600 dark:text-gray-400 text-[11px] break-all">{{ $record->checksum }}</code>
                </div>
            @endif
        </div>
    </div>

    {{-- File 2: Answer Key --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-xs">
        <div class="flex items-start justify-between gap-3 mb-2.5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs">
                    KEY
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white text-base">Answer Key & Teacher Solutions</h4>
                    <span class="text-xs text-gray-400">Optional verified answer guide</span>
                </div>
            </div>
            @if($answerExists && !empty($answerUrl))
                <a href="{{ $answerUrl }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Open Answer Key
                </a>
            @endif
        </div>

        @if(!empty($record->answer_file_path))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs bg-gray-50 dark:bg-gray-900/40 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
                <div>
                    <span class="text-gray-400 block">Storage Path:</span>
                    <code class="text-gray-800 dark:text-gray-200 break-all">{{ $record->answer_file_path }}</code>
                </div>
                <div>
                    <span class="text-gray-400 block">Status & Size:</span>
                    <span class="font-medium {{ $answerExists ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600' }}">
                        {{ $answerExists ? '✓ Stored on private disk (' . $answerSize . ')' : '⚠ File missing on storage' }}
                    </span>
                </div>
            </div>
        @else
            <div class="p-3 bg-gray-50 dark:bg-gray-900/20 rounded-lg text-xs text-gray-500 italic">
                No answer key has been uploaded for this version.
            </div>
        @endif
    </div>

    {{-- File 3: Low-Ink Variant --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-xs">
        <div class="flex items-start justify-between gap-3 mb-2.5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs">
                    ECO
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white text-base">Low-Ink / Black & White Edition</h4>
                    <span class="text-xs text-gray-400">Optional ink-saver printable variant</span>
                </div>
            </div>
            @if($lowInkExists && !empty($lowInkUrl))
                <a href="{{ $lowInkUrl }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Open Low-Ink PDF
                </a>
            @endif
        </div>

        @if(!empty($record->low_ink_path))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs bg-gray-50 dark:bg-gray-900/40 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
                <div>
                    <span class="text-gray-400 block">Storage Path:</span>
                    <code class="text-gray-800 dark:text-gray-200 break-all">{{ $record->low_ink_path }}</code>
                </div>
                <div>
                    <span class="text-gray-400 block">Status & Size:</span>
                    <span class="font-medium {{ $lowInkExists ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600' }}">
                        {{ $lowInkExists ? '✓ Stored on private disk (' . $lowInkSize . ')' : '⚠ File missing on storage' }}
                    </span>
                </div>
            </div>
        @else
            <div class="p-3 bg-gray-50 dark:bg-gray-900/20 rounded-lg text-xs text-gray-500 italic">
                No low-ink variant has been uploaded for this version.
            </div>
        @endif
    </div>

    {{-- Previews Preview Section if available --}}
    @if($record->previews->isNotEmpty())
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-xs">
            <h4 class="font-semibold text-gray-900 dark:text-white text-base mb-2">Generated Previews ({{ $record->previews->count() }} pages)</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($record->previews as $preview)
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-50 dark:bg-gray-900">
                        <img src="{{ Storage::disk('previews')->url($preview->image_path) }}" alt="Page {{ $preview->page_no }}" class="w-full h-auto object-cover" />
                        <div class="p-1.5 text-center text-[11px] font-medium text-gray-600 dark:text-gray-400">
                            Page {{ $preview->page_no }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
