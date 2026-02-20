<x-admin-layout>
    <x-slot name="header">Order #{{ $order->id }}</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Order Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-500">Service</span>
                        <span class="font-medium text-gray-900">{{ $order->service->name ?? 'Deleted Service' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Category</span>
                        <span class="font-medium text-gray-900">{{ $order->service->category->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Link</span>
                        <a href="{{ $order->link }}" target="_blank" class="font-medium text-blue-600 hover:underline break-all">{{ $order->link }}</a>
                    </div>
                    <div>
                        <span class="block text-gray-500">Quantity</span>
                        <span class="font-medium text-gray-900">{{ number_format($order->quantity) }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Charge</span>
                        <span class="font-bold text-gray-900">NPR {{ number_format($order->charge, 2) }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Profit</span>
                        <span class="font-bold text-green-600">NPR {{ number_format($order->profit ?? 0, 2) }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Provider Order ID</span>
                        <span class="font-mono text-gray-900">{{ $order->provider_order_id ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Order Source</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->order_source === 'api' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($order->order_source ?? 'web') }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Created</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y H:i:s') }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Updated</span>
                        <span class="font-medium text-gray-900">{{ $order->updated_at->format('M d, Y H:i:s') }}</span>
                    </div>
                </div>

                @if($order->is_drip_feed)
                <div class="mt-4 p-3 bg-purple-50 border border-purple-100 rounded-lg text-sm">
                    <span class="font-bold text-purple-900">Drip Feed:</span>
                    {{ $order->drip_feed_runs }} runs, every {{ $order->drip_feed_interval }} minutes
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        'refunded' => 'bg-red-100 text-red-800 border-red-200',
                        'partial' => 'bg-purple-100 text-purple-800 border-purple-200',
                    ];
                @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>

                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="mt-4">
                    @csrf @method('PUT')
                    <label class="block text-sm font-medium text-gray-700 mb-1">Change Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 mb-3">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="partial" {{ $order->status === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (+ Refund)</option>
                        <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Update Status
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Changing to cancelled/refunded will auto-refund user balance.</p>
                </form>
            </div>

            <!-- User Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">User</h3>
                <div class="text-sm space-y-2">
                    <div>
                        <span class="text-gray-500">Name:</span>
                        <span class="font-medium text-gray-900">{{ $order->user->name ?? 'Unknown' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Email:</span>
                        <span class="font-medium text-gray-900">{{ $order->user->email ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Balance:</span>
                        <span class="font-bold text-gray-900">NPR {{ number_format($order->user->balance ?? 0, 2) }}</span>
                    </div>
                    <a href="{{ route('admin.users.edit', $order->user_id) }}" class="inline-block mt-2 text-indigo-600 hover:underline text-sm font-medium">View User Profile &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
