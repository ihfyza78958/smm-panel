<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">API Providers Management</h2>
        <a href="{{ route('admin.providers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Provider
        </a>
    </div>

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

            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Balance</span>
                    <span class="font-bold text-gray-900">{{ number_format($provider->balance, 2) }} {{ $provider->currency }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">API Key</span>
                    <span class="font-mono text-gray-600 bg-gray-50 px-2 rounded">••••{{ substr($provider->api_key, -4) }}</span>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.providers.edit', $provider) }}" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition text-center">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.providers.destroy', $provider) }}" onsubmit="return confirm('Delete this provider?');" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-white border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <p>No API providers configured yet.</p>
        </div>
        @endforelse
    </div>
</x-admin-layout>
