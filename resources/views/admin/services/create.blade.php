<x-admin-layout>
    <x-slot name="header">Add Service</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Add New Service</h2>
        <a href="{{ route('admin.services.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl mx-auto">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.services.store') }}">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Instagram Followers - Premium" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                        <select name="smm_provider_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Manual Service</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{ old('smm_provider_id') == $provider->id ? 'selected' : '' }}>{{ $provider->domain }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provider Service ID</label>
                        <input type="text" name="provider_service_id" value="{{ old('provider_service_id') }}" placeholder="e.g. 1234" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="default" {{ old('type') == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="package" {{ old('type') == 'package' ? 'selected' : '' }}>Package</option>
                            <option value="custom_comments" {{ old('type') == 'custom_comments' ? 'selected' : '' }}>Custom Comments</option>
                            <option value="subscriptions" {{ old('type') == 'subscriptions' ? 'selected' : '' }}>Subscriptions</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price per 1000 (NPR)</label>
                        <input type="number" name="price" value="{{ old('price') }}" required step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provider Rate</label>
                        <input type="number" name="provider_rate" value="{{ old('provider_rate') }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Cost from provider</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profit Margin %</label>
                        <input type="number" name="profit_margin" value="{{ old('profit_margin', 20) }}" step="0.1" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Quantity</label>
                        <input type="number" name="min_quantity" value="{{ old('min_quantity', 10) }}" required min="1" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Quantity</label>
                        <input type="number" name="max_quantity" value="{{ old('max_quantity', 10000) }}" required min="1" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Refill Days</label>
                        <input type="number" name="refill_days" value="{{ old('refill_days', 0) }}" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">0 = no refill</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Service description...">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="cancel_allowed" value="1" {{ old('cancel_allowed') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Allow Cancel</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.services.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Create Service
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
