<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
        $user = auth()->user();
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $processingOrders = $user->orders()->where('status', 'processing')->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $totalSpent = $user->orders()->sum('charge');
    @endphp

    <!-- Welcome Banner -->
    <div class="relative bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600 rounded-2xl p-6 md:p-8 mb-8 overflow-hidden text-white">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2240%22%20height%3D%2240%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M0%2040L40%200H20L0%2020M40%2040V20L20%2040%22%20fill%3D%22rgba(255%2C255%2C255%2C0.03)%22%2F%3E%3C%2Fsvg%3E')] opacity-50"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold">Welcome back, {{ $user->name }}!</h2>
                <p class="text-emerald-100 mt-1 text-sm md:text-base">Manage your social media growth from one place.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('orders.new') }}" class="bg-white text-emerald-700 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-50 transition shadow-lg shadow-emerald-700/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Order
                </a>
                <a href="{{ route('wallet.index') }}" class="bg-white/15 backdrop-blur-sm text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-white/25 transition border border-white/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Add Funds
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Balance -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <a href="{{ route('wallet.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Top Up</a>
            </div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">NPR {{ number_format($user->balance, 2) }}</p>
        </div>

        <!-- Total Orders -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 rounded-lg bg-blue-50 text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <a href="{{ route('orders.history') }}" class="text-xs text-blue-600 font-semibold hover:underline">View</a>
            </div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Orders</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalOrders) }}</p>
        </div>

        <!-- Total Spent -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 rounded-lg bg-purple-50 text-purple-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">NPR {{ number_format($totalSpent, 2) }}</p>
        </div>

        <!-- In Progress -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 rounded-lg bg-amber-50 text-amber-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">In Progress</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">{{ $pendingOrders + $processingOrders }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="{{ route('orders.new') }}" class="bg-white rounded-xl border border-gray-100 p-4 text-center hover:shadow-md hover:border-emerald-200 transition group">
                    <div class="w-10 h-10 mx-auto rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">New Order</span>
                </a>
                <a href="{{ route('orders.mass') }}" class="bg-white rounded-xl border border-gray-100 p-4 text-center hover:shadow-md hover:border-blue-200 transition group">
                    <div class="w-10 h-10 mx-auto rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Mass Order</span>
                </a>
                <a href="{{ route('wallet.index') }}" class="bg-white rounded-xl border border-gray-100 p-4 text-center hover:shadow-md hover:border-purple-200 transition group">
                    <div class="w-10 h-10 mx-auto rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Add Funds</span>
                </a>
                <a href="{{ route('guest.services') }}" class="bg-white rounded-xl border border-gray-100 p-4 text-center hover:shadow-md hover:border-orange-200 transition group">
                    <div class="w-10 h-10 mx-auto rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Services</span>
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Recent Orders</h3>
                    <a href="{{ route('orders.history') }}" class="text-xs text-emerald-600 font-bold hover:underline">View All &rarr;</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="flex-shrink-0">
                                    @php
                                        $statusIcon = match($order->status) {
                                            'completed' => ['bg-green-50 text-green-600', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'processing' => ['bg-blue-50 text-blue-600', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                            'pending' => ['bg-amber-50 text-amber-600', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'partial' => ['bg-purple-50 text-purple-600', 'M13 10V3L4 14h7v7l9-11h-7z'],
                                            'cancelled', 'canceled' => ['bg-red-50 text-red-600', 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            default => ['bg-gray-50 text-gray-600', 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        };
                                    @endphp
                                    <div class="w-9 h-9 rounded-lg {{ $statusIcon[0] }} flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon[1] }}"></path></svg>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $order->service->name ?? 'Service Removed' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        #{{ $order->id }} &bull; {{ number_format($order->quantity) }} qty &bull; {{ $order->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <p class="text-sm font-bold text-gray-900">NPR {{ number_format($order->charge, 2) }}</p>
                                @php
                                    $statusClass = match($order->status) {
                                        'completed' => 'bg-green-100 text-green-700',
                                        'processing' => 'bg-blue-100 text-blue-700',
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'partial' => 'bg-purple-100 text-purple-700',
                                        'cancelled', 'canceled' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold uppercase {{ $statusClass }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="text-gray-500 font-medium">No orders yet</p>
                            <p class="text-gray-400 text-sm mt-1">Place your first order to get started!</p>
                            <a href="{{ route('orders.new') }}" class="inline-flex items-center gap-1.5 mt-4 bg-emerald-600 text-white px-5 py-2 rounded-lg font-semibold text-sm hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Place Order
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Order Status Breakdown -->
            @if($totalOrders > 0)
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                    <div class="text-2xl font-bold text-amber-600">{{ $pendingOrders }}</div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">Pending</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $processingOrders }}</div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">Processing</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $completedOrders }}</div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">Completed</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Announcements -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        Announcements
                    </h3>
                    @if(count($announcements) > 0)
                        <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">{{ count($announcements) }}</span>
                    @endif
                </div>
                <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                    @forelse($announcements as $ann)
                        <div class="px-5 py-3">
                            <p class="text-sm font-semibold text-gray-900">{{ $ann->title }}</p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ strip_tags($ann->content) }}</p>
                            <p class="text-[10px] text-gray-400 mt-1.5">{{ $ann->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-sm text-gray-400">
                            <svg class="w-8 h-8 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            No announcements
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Redeem Coupon -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5" x-data="{ msg: '', err: false }">
                <h3 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Redeem Coupon
                </h3>
                <form method="POST" action="{{ route('coupon.redeem') }}" class="flex gap-2"
                    @submit.prevent="
                        const fd = new FormData($el);
                        fetch($el.action, { method: 'POST', body: fd, headers: { 'Accept': 'application/json' } })
                            .then(r => r.json())
                            .then(d => { msg = d.message || d.error || 'Done'; err = !d.success; if(d.success) setTimeout(() => location.reload(), 1500); })
                            .catch(() => { msg = 'Something went wrong'; err = true; })
                    ">
                    @csrf
                    <input type="text" name="code" placeholder="Enter coupon code" required
                        class="flex-1 rounded-lg border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400">
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-amber-600 transition whitespace-nowrap">
                        Redeem
                    </button>
                </form>
                <p x-show="msg" x-transition class="text-xs mt-2 font-medium" :class="err ? 'text-red-500' : 'text-green-600'" x-text="msg"></p>
            </div>

            <!-- API Key Quick Access -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    API Access
                </h3>
                @if($user->api_key)
                    <div class="bg-gray-50 rounded-lg px-3 py-2 font-mono text-xs text-gray-600 truncate border border-gray-100" title="{{ $user->api_key }}">
                        {{ substr($user->api_key, 0, 12) }}...{{ substr($user->api_key, -6) }}
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">Endpoint: <code class="bg-gray-100 px-1 rounded">{{ url('/api/v2') }}</code></p>
                @else
                    <p class="text-xs text-gray-500 mb-2">Generate an API key to place orders programmatically.</p>
                @endif
                <a href="{{ route('profile.edit') }}" class="block mt-3 text-center text-xs text-emerald-600 hover:underline font-semibold">Manage API Key &rarr;</a>
            </div>

            <!-- Support CTA -->
            <div class="bg-gradient-to-br from-emerald-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white text-center">
                <h3 class="font-bold text-lg mb-1">Need Help?</h3>
                <p class="text-emerald-100 text-xs mb-4">Our support team is ready to assist you.</p>
                <a href="{{ route('tickets.create') }}" class="inline-block bg-white text-emerald-600 px-6 py-2 rounded-lg font-bold text-sm hover:bg-emerald-50 transition shadow-md">Contact Support</a>
            </div>
        </div>
    </div>
</x-app-layout>
