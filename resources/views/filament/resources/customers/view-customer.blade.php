<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Account Summary -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Customer Account Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400 block">Name</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $record->name }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400 block">Email</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $record->email }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400 block">Email Verified</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">
                        {{ $record->email_verified_at ? $record->email_verified_at->format('M j, Y') : 'Unverified' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400 block">Registered On</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $record->created_at->format('M j, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Learning Profiles (Strict privacy: nickname and class ONLY) -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Learning Profiles</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Under child privacy rules, profiles show nickname and class only.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @forelse($record->learningProfiles as $profile)
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-gray-900 dark:text-gray-100 block">{{ $profile->nickname }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Class: {{ $profile->schoolClass?->name ?? 'None' }}</span>
                        </div>
                        <span class="text-xs px-2 py-1 bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-200 rounded-full font-medium">Profile #{{ $profile->id }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 col-span-3">No learning profiles created yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Membership & Subscriptions -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Membership & Subscriptions</h2>
            @php
                $subscriptions = \App\Domains\Membership\Models\Subscription::query()->where('user_id', $record->id)->with('plan')->get();
            @endphp
            @if($subscriptions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-2">Plan</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Starts At</th>
                                <th class="px-4 py-2">Expires At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($subscriptions as $sub)
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $sub->plan?->name ?? 'Pilot Membership' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $sub->isActive() ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                                            {{ $sub->status->value }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $sub->starts_at?->format('M j, Y') ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $sub->ends_at?->format('M j, Y') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No subscriptions found for this parent.</p>
            @endif
        </div>

        <!-- Orders & Payments -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Orders & Payment State</h2>
            @if($record->orders->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-2">Order #</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Total Amount</th>
                                <th class="px-4 py-2">Payment Details</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($record->orders as $order)
                                @php
                                    $payment = $order->payments()->latest()->first();
                                @endphp
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700">
                                            {{ is_object($order->status) ? $order->status->value : $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-mono">₹{{ number_format($order->total / 100, 2) }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($payment)
                                            <span class="block">ID: {{ $payment->provider_payment_id ?? 'Pending' }}</span>
                                            <span class="block text-gray-500">Status: {{ is_object($payment->status) ? $payment->status->value : $payment->status }}</span>
                                        @else
                                            <span class="text-gray-500">No payment record</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">{{ $order->created_at->format('M j, Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No orders placed by this customer.</p>
            @endif
        </div>

        <!-- Library Entitlements -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Library Entitlements (Active & Granted Access)</h2>
            @php
                $entitlements = \App\Domains\Access\Models\Entitlement::query()->where('user_id', $record->id)->with('resource')->latest('starts_at')->get();
            @endphp
            @if($entitlements->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-2">Resource</th>
                                <th class="px-4 py-2">Source</th>
                                <th class="px-4 py-2">Reason / Context</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Granted Date</th>
                                <th class="px-4 py-2">Expires</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($entitlements as $ent)
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $ent->resource?->title ?? 'Resource #'.$ent->resource_id }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-xs {{ $ent->source_type === 'admin_grant' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' }}">
                                            {{ $ent->source_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                        {{ $ent->metadata['reason'] ?? 'Purchase / standard access' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($ent->isValid())
                                            <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 font-semibold">Active</span>
                                        @elseif($ent->revoked_at)
                                            <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 font-semibold">Revoked</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 font-semibold">Expired</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs">{{ $ent->starts_at?->format('M j, Y') ?? '—' }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $ent->ends_at?->format('M j, Y') ?? 'Never' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No entitlements found for this parent.</p>
            @endif
        </div>

        <!-- Support Notes -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Internal Support Notes</h2>
            @if($record->supportNotes->isNotEmpty())
                <div class="space-y-3">
                    @foreach($record->supportNotes()->with('author')->latest()->get() as $note)
                        <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $note->author?->email ?? 'Staff Member' }}</span>
                                <span>{{ $note->created_at->format('M j, Y H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $note->note }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No support notes recorded yet.</p>
            @endif
        </div>
    </div>
</x-filament-panels::page>
