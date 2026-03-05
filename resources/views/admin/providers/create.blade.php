<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Add New Provider</h2>
        <a href="{{ route('admin.providers.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.providers.store') }}">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domain Name</label>
                    <input type="text" name="domain" required placeholder="e.g. justanotherpanel.com" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Just for identification</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API URL</label>
                    <input type="url" name="url" required placeholder="https://justanotherpanel.com/api/v2" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                    <input type="text" name="api_key" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="USD">USD</option>
                        <option value="INR">INR</option>
                        <option value="NPR">NPR</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conversion Rate to Local Price</label>
                    <input type="number" name="conversion_rate" value="1" step="0.000001" min="0.000001" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Example: if provider is USD and your local pricing is NPR, set USD→NPR rate here (e.g. 133.50).</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Save Provider
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
