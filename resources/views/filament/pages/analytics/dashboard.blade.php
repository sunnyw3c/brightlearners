<x-filament-panels::page>
    @php
        $data = $this->getSummaryData();
    @endphp

    <div class="space-y-6">
        {{-- Header Controls & Navigation --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Performance Window</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Reconciled first-party analytics and financial ledger metrics</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ \App\Filament\Pages\Analytics\PilotCohort::getUrl() }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/40 dark:text-amber-300 dark:hover:bg-amber-900/50 border border-amber-200 dark:border-amber-800 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pilot Cohort View (20–30 Families)
                </a>
            </div>
        </div>

        {{-- Headline KPI Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Visitors</div>
                <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['visitors']) }}</div>
                <div class="mt-1 text-xs text-gray-400">Distinct anonymous/user visits</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Free Downloads</div>
                <div class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($data['freeDownloaders']) }}</div>
                <div class="mt-1 text-xs text-gray-400">Worksheets delivered</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Net Revenue (Reconciled)</div>
                <div class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">₹{{ number_format($data['netRevenueInr'], 2) }}</div>
                <div class="mt-1 text-xs text-gray-400">Captured payments − refunds</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Average Order Value</div>
                <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">₹{{ number_format($data['aovInr'], 2) }}</div>
                <div class="mt-1 text-xs text-gray-400">{{ number_format($data['paidOrders']) }} paid orders</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Paid Conversion Rate</div>
                <div class="mt-1 text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $data['conversionRate'] }}%</div>
                <div class="mt-1 text-xs text-gray-400">Purchasers ÷ visitors</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Active Members</div>
                <div class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($data['activeMembers']) }}</div>
                <div class="mt-1 text-xs text-gray-400">Active non-expired subscriptions</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Weekly Active Learners</div>
                <div class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($data['weeklyActiveMembers']) }}</div>
                <div class="mt-1 text-xs text-gray-400">Opened packs in past 7 days</div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Members Started</div>
                <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['membersStarted']) }}</div>
                <div class="mt-1 text-xs text-gray-400">New memberships activated</div>
            </div>
        </div>

        {{-- The Funnel Pipeline --}}
        <div class="p-6 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Core Conversion Funnel</h3>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                    <span class="inline-block px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-gray-500 bg-gray-200/60 dark:bg-gray-700 dark:text-gray-300 rounded mb-2">1. Visitor</span>
                    <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['visitors']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">100% baseline</div>
                </div>

                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                    <span class="inline-block px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-amber-600 bg-amber-100/60 dark:bg-amber-950/60 dark:text-amber-400 rounded mb-2">2. Free Resource</span>
                    <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['freeDownloaders']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $data['visitors'] > 0 ? round(($data['freeDownloaders'] / $data['visitors']) * 100, 1) : 0 }}% of visitors
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                    <span class="inline-block px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-100/60 dark:bg-indigo-950/60 dark:text-indigo-400 rounded mb-2">3. Product View</span>
                    <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['productViewers']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $data['visitors'] > 0 ? round(($data['productViewers'] / $data['visitors']) * 100, 1) : 0 }}% of visitors
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                    <span class="inline-block px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-emerald-600 bg-emerald-100/60 dark:bg-emerald-950/60 dark:text-emerald-400 rounded mb-2">4. Purchase</span>
                    <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['purchasers']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $data['productViewers'] > 0 ? round(($data['purchasers'] / $data['productViewers']) * 100, 1) : 0 }}% from product view
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                    <span class="inline-block px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-blue-600 bg-blue-100/60 dark:bg-blue-950/60 dark:text-blue-400 rounded mb-2">5. Membership</span>
                    <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['membersStarted']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $data['purchasers'] > 0 ? round(($data['membersStarted'] / $data['purchasers']) * 100, 1) : 0 }}% of purchasers
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                    <span class="inline-block px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-purple-600 bg-purple-100/60 dark:bg-purple-950/60 dark:text-purple-400 rounded mb-2">6. Weekly Use</span>
                    <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($data['weeklyActiveMembers']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $data['activeMembers'] > 0 ? round(($data['weeklyActiveMembers'] / $data['activeMembers']) * 100, 1) : 0 }}% active retention
                    </div>
                </div>
            </div>
        </div>

        {{-- Daily Breakdown Table --}}
        <div class="p-6 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Historical Daily Aggregations</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 font-medium bg-gray-50 dark:bg-gray-800/50">
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Visitors</th>
                            <th class="py-2.5 px-3">Free Downloads</th>
                            <th class="py-2.5 px-3">Product Views</th>
                            <th class="py-2.5 px-3">Paid Orders</th>
                            <th class="py-2.5 px-3">Net Revenue (₹)</th>
                            <th class="py-2.5 px-3">Active Members</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($data['dailyRows'] as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40">
                                <td class="py-2.5 px-3 font-medium text-gray-900 dark:text-gray-100">{{ $row['date'] }}</td>
                                <td class="py-2.5 px-3">{{ number_format($row['visitors']) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($row['free_downloaders']) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($row['product_viewers']) }}</td>
                                <td class="py-2.5 px-3 font-semibold">{{ number_format($row['paid_orders']) }}</td>
                                <td class="py-2.5 px-3 font-semibold text-emerald-600 dark:text-emerald-400">₹{{ number_format($row['net_revenue_inr'], 2) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($row['active_members']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-400">
                                    No aggregated metrics found for this period. Run <code>php artisan analytics:aggregate-daily</code> to populate.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
