<x-filament-panels::page>
    <div class="space-y-4">
        <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Pilot Cohort Family Retention</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Aggregated progress and purchase metrics per family. Learner nicknames and sensitive profiles are omitted to protect child privacy.
                </p>
            </div>
            <a href="{{ \App\Filament\Pages\Analytics\AnalyticsDashboard::getUrl() }}" class="text-xs font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400">
                ← Back to Analytics Dashboard
            </a>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
