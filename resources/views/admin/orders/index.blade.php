<x-admin-layout>
    <x-slot name="header">Orders</x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap bg-gray-100 p-1 rounded-lg gap-0.5">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ !request('status') ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">All</a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'pending' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Pending</a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'processing' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Processing</a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'completed' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Completed</a>
            <a href="{{ route('admin.orders.index', ['status' => 'canceled']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'canceled' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Failed</a>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by ID, link, or user..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">Search</button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
           <div class="overflow-x-auto">
               <table class="w-full min-w-[980px] text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-24">Order ID</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Service Details</th>
                        <th class="px-6 py-4 font-semibold text-center">Quantity</th>
                        <th class="px-6 py-4 font-semibold text-right">Cost</th>
                         <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Created</th>
                        <th class="px-6 py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-gray-600 font-bold">#{{ $order->id }}</span>
                            @if($order->provider_order_id)
                                <div class="text-[10px] text-gray-400 mt-0.5">PID: {{ $order->provider_order_id }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4" style="min-width:180px">
                            <div class="font-medium text-gray-900 truncate" style="max-width:180px" title="{{ $order->user->name ?? 'Unknown' }}">{{ $order->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-400 truncate" style="max-width:180px" title="{{ $order->user->email ?? 'N/A' }}">{{ $order->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4" style="min-width:280px">
                            <div class="font-medium text-gray-900 truncate" style="max-width:280px" title="{{ $order->service->name ?? '' }}">{{ $order->service->name ?? 'Deleted Service' }}</div>
                            <a href="{{ $order->link }}" target="_blank" class="text-xs text-blue-500 hover:underline truncate block" style="max-width:280px" title="{{ $order->link }}">{{ $order->link }}</a>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-gray-600 whitespace-nowrap">
                            {{ number_format($order->quantity) }}
                        </td>
                         <td class="px-6 py-4 text-right whitespace-nowrap">
                             <span class="font-bold text-gray-700">NPR {{ number_format($order->charge, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'canceled' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-red-100 text-red-800',
                                    'partial' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                         <td class="px-6 py-4 text-right text-gray-500 text-xs whitespace-nowrap">
                             {{ $order->created_at->format('M d H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                             <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="8" class="px-6 py-10 text-center text-gray-500">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</x-admin-layout>
