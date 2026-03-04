<x-app-layout>
    <x-slot name="header">Order History</x-slot>

    <div class="card overflow-hidden">
        <!-- Filters -->
        <form method="GET" action="{{ route('orders.history') }}" class="flex flex-col sm:flex-row gap-4 justify-between items-center mb-6 pb-6 border-b border-gray-100">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID or Link..." class="input-field pl-10">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="flex gap-2">
                <select name="status" class="input-field w-auto text-sm py-1.5" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(['pending','processing','completed','partial','canceled','refunded'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @if(request('search') || request('status'))
                    <a href="{{ route('orders.history') }}" class="px-3 py-1.5 text-sm text-gray-500 border rounded-lg hover:bg-gray-50">Clear</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-lg">ID</th>
                        <th class="px-6 py-4">Service Details</th>
                        <th class="px-6 py-4">Link</th>
                        <th class="px-6 py-4 text-center">Quantity</th>
                        <th class="px-6 py-4 text-center">Amount</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 rounded-tr-lg">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="bg-white hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 font-bold text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->service->name ?? 'Service Removed' }}</div>
                                <div class="text-xs text-indigo-500">{{ $order->service->category->name ?? 'Category' }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate">
                                <a href="{{ $order->link }}" target="_blank" class="text-blue-500 hover:underline">{{ $order->link }}</a>
                            </td>
                            <td class="px-6 py-4 text-center font-medium">{{ number_format($order->quantity) }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-800">NPR {{ number_format($order->charge, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'completed' => 'bg-green-100 text-green-700 border-green-200',
                                        'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'canceled' => 'bg-red-100 text-red-700 border-red-200',
                                        'refunded' => 'bg-red-100 text-red-700 border-red-200',
                                        'partial' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    ];
                                    $class = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $class }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs text-right">
                                {{ $order->created_at->format('M d, Y') }}<br>
                                {{ $order->created_at->format('H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-400">
                                No orders found. <a href="{{ route('orders.new') }}" class="text-indigo-600 font-bold hover:underline">Place an order</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
