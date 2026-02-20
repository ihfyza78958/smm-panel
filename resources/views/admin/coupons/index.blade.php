<x-admin-layout>
    <x-slot name="header">Coupons</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Coupon Management</h2>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Create Form -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Create Coupon</h3>
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-xs text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.coupons.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                            <input type="text" name="code" value="{{ old('code') }}" required placeholder="WELCOME50" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono uppercase">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="type" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed (NPR)</option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                <input type="number" name="value" value="{{ old('value') }}" required step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Uses</label>
                                <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="0" placeholder="0 = unlimited" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Per User</label>
                                <input type="number" name="max_uses_per_user" value="{{ old('max_uses_per_user', 1) }}" min="1" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expires At</label>
                            <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Leave empty for no expiry</p>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                            Create Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Coupons List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Code</th>
                                <th class="px-6 py-4 font-semibold">Type</th>
                                <th class="px-6 py-4 font-semibold text-right">Value</th>
                                <th class="px-6 py-4 font-semibold text-center">Uses</th>
                                <th class="px-6 py-4 font-semibold">Expires</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($coupons as $coupon)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4 font-mono font-bold text-gray-900">{{ $coupon->code }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->type === 'percentage' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($coupon->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800">
                                    {{ $coupon->type === 'percentage' ? $coupon->value . '%' : 'NPR ' . number_format($coupon->value, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600">
                                    {{ $coupon->used_count ?? 0 }} / {{ $coupon->max_uses ?: '&infin;' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete this coupon?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">No coupons yet. Create one using the form.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($coupons->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $coupons->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
