<x-app-layout>
    <x-slot name="header">Mass Order</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Mass Order Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Mass Order</h3>
                <p class="text-sm text-gray-500 mb-6">Place multiple orders at once using the pipe-separated format below.</p>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('orders.mass.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Orders (one per line)</label>
                            <textarea name="orders" rows="10" required
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                                placeholder="service_id|link|quantity&#10;1|https://instagram.com/username|1000&#10;2|https://youtube.com/watch?v=xyz|5000&#10;3|https://tiktok.com/@user/video/123|2000">{{ old('orders') }}</textarea>
                        </div>
                        <button type="submit" class="w-full btn-primary py-3 text-base shadow-lg shadow-indigo-200">
                            Submit Mass Order
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Format Guide -->
            <div class="card bg-gray-900 text-white border-0">
                <h3 class="text-lg font-bold mb-4">Format Guide</h3>
                <div class="space-y-3 text-sm text-gray-300">
                    <p>Each line should follow this format:</p>
                    <code class="block p-3 bg-gray-800 rounded-lg text-green-400 text-xs">
                        service_id|link|quantity
                    </code>
                    <p class="text-xs text-gray-400 mt-2">Separate each field with a pipe <code class="text-green-400">|</code> character.</p>
                </div>
            </div>

            <!-- Example -->
            <div class="card bg-blue-50 border-blue-100">
                <h3 class="text-sm font-bold text-blue-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Example
                </h3>
                <div class="mt-3 p-3 bg-white rounded-lg border border-blue-100">
                    <code class="text-xs text-gray-700 block whitespace-pre-wrap">1|https://instagram.com/user|1000
2|https://youtube.com/watch?v=abc|5000
3|https://tiktok.com/@user/video/123|2000</code>
                </div>
            </div>

            <!-- Important Rules -->
            <div class="card bg-orange-50 border-orange-100">
                <h3 class="text-sm font-bold text-orange-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    Important
                </h3>
                <ul class="mt-3 space-y-2 text-xs text-orange-700 list-disc list-inside">
                    <li>All orders are validated before any are placed.</li>
                    <li>Your total balance must cover all orders.</li>
                    <li>Invalid lines will cause the entire batch to fail.</li>
                    <li>Check service IDs on the Services page.</li>
                </ul>
            </div>

            <!-- Balance -->
            <div class="card">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Your Balance</span>
                    <span class="text-lg font-bold text-gray-900">NPR {{ number_format(auth()->user()->balance, 2) }}</span>
                </div>
                <a href="{{ route('wallet.index') }}" class="block mt-3 text-center text-sm text-indigo-600 hover:underline font-medium">Add Funds &rarr;</a>
            </div>
        </div>
    </div>
</x-app-layout>
