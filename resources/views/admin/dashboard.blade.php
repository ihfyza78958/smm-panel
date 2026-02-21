<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Stats Row 1 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                @if($stats['revenue_growth'] != 0)
                <span class="text-xs font-semibold px-2 py-1 rounded {{ $stats['revenue_growth'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $stats['revenue_growth'] > 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}%
                </span>
                @endif
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">NPR {{ number_format($stats['revenue'], 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Today: NPR {{ number_format($stats['today_revenue'], 2) }}</p>
        </div>

        <!-- Orders Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                @if($stats['today_orders'] > 0)
                <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-1 rounded">+{{ $stats['today_orders'] }} today</span>
                @endif
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['orders']) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['active_services'] }} active services</p>
        </div>

        <!-- Users Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Registered Users</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['users']) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['open_tickets'] }} open tickets</p>
        </div>

        <!-- Pending Orders Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                @if($stats['orders_pending'] > 0)
                    <span class="text-xs font-semibold bg-orange-100 text-orange-700 px-2 py-1 rounded">Action Needed</span>
                @endif
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Pending Orders</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['orders_pending']) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['pending_transactions'] }} pending transactions</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-900">Revenue Overview (Last 7 Days)</h3>
                <span class="text-xs text-gray-400">NPR</span>
            </div>
            @php
                $maxRevenue = max(array_column($chartData, 'revenue'));
                $maxRevenue = $maxRevenue > 0 ? $maxRevenue : 1;
            @endphp
            <div class="flex items-end gap-3 h-48">
                @foreach($chartData as $day)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <span class="text-xs font-semibold text-gray-700">{{ $day['revenue'] > 0 ? number_format($day['revenue'], 0) : '' }}</span>
                    <div class="w-full bg-indigo-100 rounded-t-lg relative group" style="height: {{ max(4, ($day['revenue'] / $maxRevenue) * 100) }}%;">
                        <div class="w-full h-full bg-gradient-to-t from-indigo-500 to-indigo-400 rounded-t-lg transition-all hover:from-indigo-600 hover:to-indigo-500"></div>
                        <div class="hidden group-hover:block absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs py-1 px-2 rounded whitespace-nowrap z-10">
                            {{ $day['orders'] }} orders
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $day['date'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions & Providers -->
        <div class="space-y-6">
            <!-- Quick Shortcuts -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Quick Shortcuts</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.services.create') }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        New Service
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        Pending Orders
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        Transactions
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        Settings
                    </a>
                </div>
            </div>

            <!-- Provider Health -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Provider Status</h3>
                <div class="space-y-4">
                    @forelse($providers as $provider)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $provider->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            <div class="min-w-0">
                                <span class="text-sm font-medium text-gray-700 block truncate">{{ $provider->domain ?? parse_url($provider->url, PHP_URL_HOST) ?? 'Provider' }}</span>
                                <span class="text-[10px] text-gray-400">{{ $provider->services_count }} services</span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $provider->is_active ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                {{ $provider->is_active ? 'Active' : 'Disabled' }}
                            </span>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ number_format($provider->balance, 2) }} {{ $provider->currency }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">No providers configured</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Recent Orders + Top Services -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 pb-0 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm mt-4">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Order</th>
                            <th class="px-6 py-3 text-left font-semibold">User</th>
                            <th class="px-6 py-3 text-right font-semibold">Amount</th>
                            <th class="px-6 py-3 text-center font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline font-mono text-xs font-bold">#{{ $order->id }}</a>
                            </td>
                            <td class="px-6 py-3 text-gray-700 text-xs">{{ $order->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-3 text-right font-bold text-gray-800 text-xs">NPR {{ number_format($order->charge, 2) }}</td>
                            <td class="px-6 py-3 text-center">
                                @php
                                    $sc = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'canceled' => 'bg-red-100 text-red-800',
                                        'refunded' => 'bg-red-100 text-red-800',
                                        'partial' => 'bg-purple-100 text-purple-800',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $sc[$order->status] ?? 'bg-gray-100 text-gray-800' }}">{{ ucfirst($order->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-6 text-center text-gray-400 text-xs">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 pb-0 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Top Services</h3>
                <a href="{{ route('admin.services.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm mt-4">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Service</th>
                            <th class="px-6 py-3 text-right font-semibold">Orders</th>
                            <th class="px-6 py-3 text-right font-semibold">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($topServices as $service)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-900 text-xs truncate max-w-[200px]" title="{{ $service->name }}">{{ $service->name }}</div>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">{{ $service->orders_count }}</span>
                            </td>
                            <td class="px-6 py-3 text-right font-bold text-gray-700 text-xs">NPR {{ number_format($service->price, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-6 text-center text-gray-400 text-xs">No services yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
