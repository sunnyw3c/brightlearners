<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span class="text-primary-500 font-bold">⚡ Quick Admin Actions</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-primary-100 text-primary-800 dark:bg-primary-950 dark:text-primary-300 font-medium">Fast Operations</span>
            </div>
        </x-slot>
        <x-slot name="description">
            Direct shortcuts to frequently accessed administrative workflows.
        </x-slot>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-3 mt-1">
            <a href="{{ url('/admin/learning-resources/create') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">New Resource</span>
                <span class="text-[10px] text-gray-400">Worksheet / PDF</span>
            </a>

            <a href="{{ url('/admin/products/create') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">New Product</span>
                <span class="text-[10px] text-gray-400">Workbook / Bundle</span>
            </a>

            <a href="{{ url('/admin/coupons/create') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">New Coupon</span>
                <span class="text-[10px] text-gray-400">Discount Code</span>
            </a>

            <a href="{{ url('/admin/review-queue') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-purple-500 dark:hover:border-purple-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Review Queue</span>
                <span class="text-[10px] text-gray-400">Teacher Approvals</span>
            </a>

            <a href="{{ url('/admin/orders') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-orange-500 dark:hover:border-orange-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Orders</span>
                <span class="text-[10px] text-gray-400">Sales & Receipts</span>
            </a>

            <a href="{{ url('/admin/customers') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-rose-500 dark:hover:border-rose-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Parents</span>
                <span class="text-[10px] text-gray-400">Profiles & Kids</span>
            </a>

            <a href="{{ url('/admin/school-classes') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-teal-500 dark:hover:border-teal-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Curriculum</span>
                <span class="text-[10px] text-gray-400">Class & Topics</span>
            </a>

            <a href="{{ url('/admin/analytics-dashboard') }}"
               class="group flex flex-col items-center text-center p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-md transition duration-150">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">Analytics</span>
                <span class="text-[10px] text-gray-400">Funnel & KPIs</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
