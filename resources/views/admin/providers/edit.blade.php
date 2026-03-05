<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Provider</h2>
        <div class="flex items-center gap-4">
            <form method="POST" action="{{ route('admin.providers.destroy', $provider) }}" onsubmit="return confirm('Delete this provider?');">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
            </form>
            <a href="{{ route('admin.providers.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.providers.update', $provider) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domain Name</label>
                    <input type="text" name="domain" value="{{ $provider->domain }}" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API URL</label>
                    <input type="url" name="url" value="{{ $provider->url }}" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                    <input type="text" name="api_key" value="{{ $provider->api_key }}" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <select name="currency" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="USD" {{ $provider->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="INR" {{ $provider->currency == 'INR' ? 'selected' : '' }}>INR</option>
                            <option value="NPR" {{ $provider->currency == 'NPR' ? 'selected' : '' }}>NPR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Conversion Rate to Local Price</label>
                        <input type="number" name="conversion_rate" value="{{ $provider->conversion_rate ?? 1 }}" step="0.000001" min="0.000001" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="is_active" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ $provider->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$provider->is_active ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Update Provider
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
