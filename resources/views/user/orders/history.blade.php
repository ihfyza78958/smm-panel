<x-app-layout>
    <x-slot name="header">Order History</x-slot>

    <div class="card overflow-hidden bg-white shadow-sm rounded-xl">
        <!-- Filters -->
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('orders.history') }}" class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                <div class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID or Link..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <select name="status" class="w-full sm:w-auto bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 pr-8" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach(['pending','processing','completed','partial','canceled','refunded'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    @if(request('search') || request('status'))
                        <a href="{{ route('orders.history') }}" class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors whitespace-nowrap">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Orders List -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-24">Order</th>
                        <th class="px-6 py-4 font-semibold">Service & Link</th>
                        <th class="px-6 py-4 font-semibold text-center">Counters</th>
                        <th class="px-6 py-4 font-semibold text-right">Amount</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-indigo-50/30 transition duration-150 group">
                            <!-- Order ID & Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900 text-base">#{{ $order->id }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i a') }}</div>
                            </td>
                            
                            <!-- Service Details -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 mb-1 max-w-md line-clamp-2" title="{{ $order->service->name ?? 'Service Removed' }}">
                                    {{ $order->service->name ?? 'Service Removed' }}
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded">
                                        {{ $order->service->category->name ?? 'Category' }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ $order->link }}" target="_blank" class="text-xs text-blue-500 hover:text-blue-700 hover:underline max-w-sm truncate inline-flex items-center gap-1 group-hover:bg-blue-50 px-1 -ml-1 rounded transition-colors" title="{{ $order->link }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        {{ Str::limit($order->link, 40) }}
                                    </a>
                                </div>
                            </td>

                            <!-- Organized Counters (Merged logic) -->
                            <td class="px-6 py-4">
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                    <div class="flex flex-col">
                                        <span class="text-gray-400 uppercase text-[10px] tracking-wider font-semibold">Ordered</span>
                                        <span class="font-medium text-gray-900">{{ number_format($order->quantity) }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-gray-400 uppercase text-[10px] tracking-wider font-semibold">Remains</span>
                                        <span class="font-medium text-gray-600">{{ $order->remains !== null ? number_format($order->remains) : "-" }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-gray-400 uppercase text-[10px] tracking-wider font-semibold">Start Count</span>
                                        <span class="font-medium text-gray-600">{{ $order->start_count !== null ? number_format($order->start_count) : "-" }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-emerald-600 uppercase text-[10px] tracking-wider font-bold">Final Count</span>
                                        <span class="font-bold text-emerald-600">
                                            {{ ($order->start_count !== null && $order->remains !== null) ? number_format($order->start_count + $order->quantity - $order->remains) : "-" }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="font-bold text-gray-900">
                                    <span class="text-gray-500 text-xs mr-0.5">NPR</span>{{ number_format($order->charge, 2) }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                        'processing' => 'bg-blue-50 text-blue-700 border-blue-200/60',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                        'canceled' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                        'refunded' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                        'partial' => 'bg-purple-50 text-purple-700 border-purple-200/60',
                                    ];
                                    $class = $statusClasses[$order->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    
                                    $statusIcons = [
                                        'completed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                                        'processing' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>',
                                        'pending' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                        'canceled' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                                    ];
                                    $icon = $statusIcons[$order->status] ?? '';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md text-xs font-semibold border {{ $class }}">
                                    @if($icon)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                                    @endif
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 text-center">
                                @if($order->status === 'completed' && isset($order->service) && $order->service->refill_available)
                                    <form method="POST" action="{{ route('orders.refill', $order->id) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all shadow-sm group-hover:shadow focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Refill
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-300 font-bold">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No orders found</h3>
                                    <p class="text-gray-500 text-sm mb-4">You haven't placed any orders matching this criteria yet.</p>
                                    <a href="{{ route('orders.new') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        Place New Order
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>