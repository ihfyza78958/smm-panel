<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
        
        <!-- Filter Tabs -->
        <div class="flex bg-gray-100 p-1 rounded-lg">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ !request('status') ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">All</a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'pending' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Pending</a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'processing' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Processing</a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'completed' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Completed</a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'cancelled' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white/50' }} transition">Failed</a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
             <table class="w-full text-sm text-left">
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
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-600 font-bold">#{{ $order->id }}</span>
                            @if($order->provider_order_id)
                                <div class="text-[10px] text-gray-400 mt-0.5">PID: {{ $order->provider_order_id }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $order->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-400">{{ $order->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <div class="font-medium text-gray-900 truncate" title="{{ $order->service->name ?? '' }}">{{ $order->service->name ?? 'Deleted Service' }}</div>
                            <a href="{{ $order->link }}" target="_blank" class="text-xs text-blue-500 hover:underline truncate block max-w-[200px]">{{ $order->link }}</a>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-gray-600">
                            {{ number_format($order->quantity) }}
                        </td>
                         <td class="px-6 py-4 text-right">
                             <span class="font-bold text-gray-700">NPR {{ number_format($order->charge, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'partial' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                         <td class="px-6 py-4 text-right text-gray-500 text-xs">
                             {{ $order->created_at->format('M d H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
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
