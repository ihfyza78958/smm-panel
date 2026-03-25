<x-admin-layout>
    <x-slot name="header">Services</x-slot>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Services Management</h2>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('admin.providers.index') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Import from API
            </a>
            <a href="{{ route('admin.services.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Manual
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700 font-medium">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="mb-4 flex gap-2">
        <a href="{{ route('admin.services.index') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium border {{ !request('filter') ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">All Services</a>
        <a href="{{ route('admin.services.index', ['filter' => 'dead']) }}" class="px-3 py-1.5 rounded-lg text-sm font-medium border {{ request('filter') === 'dead' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' }}">Show Dead Services</a>
    </div>

    <!-- Bulk Price Update -->
    <div x-data="{ showBulk: false }" class="mb-4">
        <button @click="showBulk = !showBulk" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            Bulk Price Update
        </button>
        <div x-show="showBulk" x-transition class="mt-3 bg-amber-50 border border-amber-200 rounded-xl p-4">
            <form method="POST" action="{{ route('admin.services.bulk-update-prices') }}" class="flex flex-wrap items-end gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-amber-800 mb-1">Profit Margin %</label>
                    <input type="number" name="margin" value="20" min="0" step="0.1" class="w-24 rounded-lg border-amber-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-amber-800 mb-1">Category (optional)</label>
                    <select name="category_id" class="rounded-lg border-amber-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="">All categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" onclick="return confirm('This will recalculate prices for all services with a provider rate. Continue?')" class="bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-700 transition">
                    Apply Margin
                </button>
            </form>
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
            <table class="w-full text-sm text-left" style="min-width:1180px">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-4 font-semibold w-12">ID</th>
                        <th class="px-4 py-4 font-semibold">Service Name</th>
                        <th class="px-4 py-4 font-semibold">Category</th>
                        <th class="px-4 py-4 font-semibold">Provider</th>
                        <th class="px-4 py-4 font-semibold text-right">Cost</th>
                        <th class="px-4 py-4 font-semibold text-right">Price (NPR)</th>
                        <th class="px-4 py-4 font-semibold text-right">Margin</th>
                        <th class="px-4 py-4 font-semibold text-center">Min / Max</th>
                        <th class="px-4 py-4 font-semibold text-center">Status</th>
                        <th class="px-4 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($services as $service)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-4 py-4 text-gray-400 font-mono text-xs whitespace-nowrap">{{ $service->id }}</td>
                        <td class="px-4 py-4" style="min-width:260px">
                            <div class="font-medium text-gray-900 text-xs leading-relaxed truncate" style="max-width:260px" title="{{ $service->name }}">{{ $service->name }}</div>
                            @if($service->provider_service_id)
                                <div class="text-[10px] text-gray-400 font-mono whitespace-nowrap">PID: {{ $service->provider_service_id }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4" style="min-width:140px">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-800 truncate" style="max-width:130px" title="{{ $service->category->name ?? 'N/A' }}">
                                {{ $service->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-4" style="min-width:140px">
                            @if($service->provider)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 truncate" style="max-width:130px" title="{{ $service->provider->domain }}">
                                    {{ $service->provider->domain }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">Manual</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right text-xs text-gray-500 font-mono whitespace-nowrap">
                            {{ $service->provider_rate ? number_format($service->provider_rate, 4) : '—' }}
                        </td>
                        <td class="px-4 py-4 text-right font-bold text-gray-800 whitespace-nowrap">
                            {{ number_format($service->price, 2) }}
                        </td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            @if($service->provider_rate && $service->provider_rate > 0)
                                @php $margin = (($service->price - $service->provider_rate) / $service->provider_rate) * 100; @endphp
                                <span class="text-xs font-semibold {{ $margin > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($margin, 1) }}%
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center text-xs text-gray-500 whitespace-nowrap">
                            {{ $service->min_quantity }} - {{ $service->max_quantity }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <form method="POST" action="{{ route('admin.services.toggle', $service) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $service->is_active ? 'Active' : 'Off' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 text-right opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.services.edit', $service) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-10 text-center text-gray-500">
                            <p>No services found.</p>
                            <a href="{{ route('admin.providers.index') }}" class="text-indigo-600 hover:underline text-sm font-medium mt-2 inline-block">Import from API provider &rarr;</a>
                        </td>
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
