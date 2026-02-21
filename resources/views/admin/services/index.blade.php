<x-admin-layout>
    <x-slot name="header">Services</x-slot>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Services Management</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.services.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Service
            </a>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="flex overflow-x-auto pb-4 gap-2 mb-4 no-scrollbar">
        <a href="{{ route('admin.services.index') }}" class="px-4 py-2 {{ !request('category') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }} rounded-full text-sm font-semibold whitespace-nowrap transition">All Services</a>
        @foreach($categories as $category)
            <a href="{{ route('admin.services.index', ['category' => $category->id]) }}" class="px-4 py-2 {{ request('category') == $category->id ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }} rounded-full text-sm font-medium whitespace-nowrap transition">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- Services Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-16">ID</th>
                        <th class="px-6 py-4 font-semibold">Service Name</th>
                        <th class="px-6 py-4 font-semibold">Category</th>
                        <th class="px-6 py-4 font-semibold text-right">Price (NPR)</th>
                        <th class="px-6 py-4 font-semibold text-center">Min / Max</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($services as $service)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $service->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $service->name }}</div>
                            <div class="text-xs text-gray-400">Provider ID: {{ $service->provider_service_id ?? 'N/A' }}</div>
                        </td>
                         <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-800">
                                {{ $service->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            {{ number_format($service->price, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-gray-500">
                            {{ $service->min_quantity }} - {{ $service->max_quantity }}
                        </td>
                         <td class="px-6 py-4 text-center">
                             <form method="POST" action="{{ route('admin.services.toggle', $service) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.services.edit', $service) }}" class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">No services found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
             {{ $services->links() }}
        </div>
    </div>
</x-admin-layout>
