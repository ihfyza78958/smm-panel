<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
        <button type="submit" form="settings-form" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Save Changes
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form id="settings-form" method="POST" action="{{ route('admin.settings.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- General Settings -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Branding -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Branding & SEO</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Website Title</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Nepalboost' }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                             <select name="currency_symbol" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="NPR" {{ ($settings['currency_symbol'] ?? '') === 'NPR' ? 'selected' : '' }}>NPR (Rs.)</option>
                                <option value="INR" {{ ($settings['currency_symbol'] ?? '') === 'INR' ? 'selected' : '' }}>INR (&#8377;)</option>
                                <option value="USD" {{ ($settings['currency_symbol'] ?? '') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
                            <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                         <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">{{ $settings['meta_description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Configuration -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Payment Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Deposit (NPR)</label>
                            <input type="number" name="min_deposit" value="{{ $settings['min_deposit'] ?? 100 }}" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Deposit (NPR)</label>
                            <input type="number" name="max_deposit" value="{{ $settings['max_deposit'] ?? 50000 }}" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="mt-4 space-y-3">
                        <label class="flex items-center justify-between p-3 border rounded-lg bg-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="bg-green-500 p-1.5 rounded-lg text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                                <div>
                                    <div class="font-medium text-gray-900">eSewa</div>
                                    <div class="text-xs text-gray-500">Local Nepal Payment</div>
                                </div>
                            </div>
                            <input type="checkbox" name="esewa_enabled" value="1" {{ ($settings['esewa_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        </label>
                        <label class="flex items-center justify-between p-3 border rounded-lg bg-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="bg-purple-500 p-1.5 rounded-lg text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </span>
                                <div>
                                    <div class="font-medium text-gray-900">Khalti</div>
                                    <div class="text-xs text-gray-500">Local Nepal Payment</div>
                                </div>
                            </div>
                            <input type="checkbox" name="khalti_enabled" value="1" {{ ($settings['khalti_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        </label>
                        <label class="flex items-center justify-between p-3 border rounded-lg bg-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-500 p-1.5 rounded-lg text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </span>
                                <div>
                                    <div class="font-medium text-gray-900">Manual Top-up</div>
                                    <div class="text-xs text-gray-500">Bank Transfer / QR</div>
                                </div>
                            </div>
                            <input type="checkbox" name="manual_topup_enabled" value="1" {{ ($settings['manual_topup_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                </div>

                <!-- Referral Settings -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Referral System</h3>
                    <div class="space-y-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="referral_enabled" value="1" {{ ($settings['referral_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Enable Referral System</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Referral Commission (%)</label>
                                <input type="number" name="referral_percentage" value="{{ $settings['referral_percentage'] ?? 5 }}" step="0.1" min="0" max="50" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Payout (NPR)</label>
                                <input type="number" name="referral_min_payout" value="{{ $settings['referral_min_payout'] ?? 500 }}" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="space-y-6">
                <!-- Maintenance Mode -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">System Status</h3>
                     <label class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700">Maintenance Mode</span>
                        <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    </label>
                    <p class="text-xs text-gray-500">Activate this to prevent users from placing new orders while you perform maintenance.</p>
                </div>

                <!-- Rates -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Global Profit Margin</h3>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Percentage (%)</label>
                        <input type="number" name="global_profit_margin" value="{{ $settings['global_profit_margin'] ?? 20 }}" step="0.1" min="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <p class="text-xs text-gray-500">Automatically adds this percentage to provider prices when syncing services.</p>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.announcements.index') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition text-sm">
                            <span class="text-gray-700 font-medium">Manage Announcements</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition text-sm">
                            <span class="text-gray-700 font-medium">Manage Coupons</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('admin.logs.index') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition text-sm">
                            <span class="text-gray-700 font-medium">Activity Logs</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
