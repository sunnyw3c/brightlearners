<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Staging & Production Notification Previews</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Use the actions in the header bar above to trigger test deliveries for each of the five notification types. 
                Notifications are dispatched to your current staff email and queued on the <code>notifications</code> queue.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">1. Order Confirmation (Transactional)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fired on order payment. Always delivered.</p>
                </div>

                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">2. Material Correction Notice (Transactional)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fired when a corrected resource version publishes. Always delivered.</p>
                </div>

                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">3. Membership Expiring Notice (Membership Notice)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fired 14 and 3 days before membership ends. Follows membership notices preference.</p>
                </div>

                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">4. Weekly Pack Released (Learning Reminder)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fired when a weekly learning pack is released. Follows learning reminders preference.</p>
                </div>

                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">5. Monthly Programme Published (Learning Reminder)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fired when a new monthly programme publishes. Follows learning reminders preference.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
