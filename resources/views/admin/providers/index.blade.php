<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">API Providers Management</h2>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.providers.update-market-rates') }}">
                @csrf
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3-7 4 18 3-7h4"></path></svg>
                    Update FX Rates
                </button>
            </form>
            <a href="{{ route('admin.providers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Provider
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700 font-medium">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700 font-medium">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($providers as $provider)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            <!-- Health Status -->
            <div class="absolute top-4 right-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $provider->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    <span class="w-2 h-2 rounded-full {{ $provider->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    {{ $provider->is_active ? 'Active' : 'Disabled' }}
                </span>
            </div>

            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $provider->domain ?? $provider->url }}</h3>
            <a href="{{ $provider->url }}" target="_blank" class="text-xs text-indigo-500 hover:underline mb-4 block">{{ $provider->url }}</a>

            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Balance</span>
                    <span class="font-bold text-gray-900">{{ number_format($provider->balance, 2) }} {{ $provider->currency }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Conversion</span>
                    <span class="font-semibold text-gray-700">× {{ number_format((float) ($provider->conversion_rate ?? 1), 6) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">API Key</span>
                    <span class="font-mono text-gray-600 bg-gray-50 px-2 rounded">••••{{ substr($provider->api_key, -4) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Services</span>
                    <span class="font-semibold text-gray-700">
                        {{ $provider->services_count ?? $provider->imported_services ?? 0 }} imported
                        @if($provider->total_services)
                            <span class="text-gray-400">/ {{ $provider->total_services }} total</span>
                        @endif
                    </span>
                </div>
                @if($provider->last_synced_at)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Last Synced</span>
                    <span class="text-gray-600">{{ $provider->last_synced_at->diffForHumans() }}</span>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2">
                <!-- Primary Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('admin.providers.services', $provider) }}" class="flex-1 bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition text-center flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Import / Sync
                    </a>
                    <form method="POST" action="{{ route('admin.providers.sync-balance', $provider) }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="bg-emerald-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-1.5" title="Refresh Balance">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Balance
                        </button>
                    </form>
                </div>
                <!-- Secondary Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('admin.providers.edit', $provider) }}" class="flex-1 bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition text-center">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.providers.destroy', $provider) }}" onsubmit="return confirm('Delete this provider and unlink all services?');" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-white border border-red-200 text-red-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <p>No API providers configured yet.</p>
            <a href="{{ route('admin.providers.create') }}" class="inline-block mt-3 text-indigo-600 hover:underline font-medium">Add your first provider &rarr;</a>
        </div>
        @endforelse
    </div>
</x-admin-layout>
