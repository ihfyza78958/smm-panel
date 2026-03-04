<x-guest-layout>
    <div class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-3xl font-extrabold sm:text-4xl">Our Services</h1>
            <p class="mt-4 text-xl text-slate-300">High-quality social media services at the best rates.</p>
        </div>
    </div>

    @php $activeCategory = request('category'); @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Category Filter Tabs -->
        <div class="flex overflow-x-auto pb-4 gap-2 mb-6 no-scrollbar">
            <a href="{{ route('guest.services') }}"
                class="px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap shadow-sm transition-all border
                       {{ !$activeCategory ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                All
            </a>
            @foreach($categories as $category)
            <a href="{{ route('guest.services', ['category' => $category->id]) }}"
                class="px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap shadow-sm transition-all border
                       {{ $activeCategory == $category->id ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <!-- Services Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-20">ID</th>
                            <th class="px-6 py-4 font-semibold">Service</th>
                            <th class="px-6 py-4 font-semibold text-right">Rate / 1000</th>
                            <th class="px-6 py-4 font-semibold text-center">Min / Max</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($services as $service)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-gray-400 font-mono text-xs">{{ $service->id }}</td>
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-900">{{ $service->name }}</div>
                                <div class="text-xs text-indigo-500 mt-0.5">{{ $service->category->name }}</div>
                                @if($service->description)
                                    <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $service->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="bg-indigo-50 text-indigo-700 font-bold px-2.5 py-1 rounded-lg text-xs">
                                    NPR {{ number_format($service->price, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center font-mono text-gray-600 text-xs">
                                {{ number_format($service->min_quantity) }} – {{ number_format($service->max_quantity) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">No services found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($services->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $services->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Create a free account</a> to start placing orders.
        </p>
    </div>
</x-guest-layout>
