<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
        <button type="submit" form="settings-form" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Save Changes
        </button>
    </div>

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

                <!-- Social Login Settings -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-1 border-b pb-2">Social Login Providers</h3>
                    <p class="text-xs text-gray-500 mb-4">Enter the OAuth credentials from each provider's developer console. The callback URL to register is shown below each provider.</p>

                    @php
                        $socialProviders = [
                            'google'   => ['label' => 'Google',   'color' => 'red',    'icon' => '<path d="M12.0003 20.45c4.656 0 8.556-3.21 9.9-7.59h-9.9v-4.14h15.24c.21 1.14.33 2.31.33 3.51 0 7.8-5.37 13.53-12.93 13.53-7.23 0-13.08-5.85-13.08-13.08S5.34 2.67 12.0003 2.67c3.48 0 6.63 1.26 9.06 3.54l-3.21 3.21c-1.5-1.44-3.51-2.34-5.85-2.34-4.8 0-8.79 3.66-10.26 8.28-1.2 3.75 1.62 7.74 5.46 8.94.99.3 2.04.45 3.09.45z" fill="currentColor"/>'],
                            'github'   => ['label' => 'GitHub',   'color' => 'gray',   'icon' => '<path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.009-.868-.013-1.703-2.782.605-3.369-1.34-3.369-1.34-.454-1.154-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.741 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z" clip-rule="evenodd" fill="currentColor"/>'],
                            'facebook' => ['label' => 'Facebook', 'color' => 'blue',   'icon' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="currentColor"/>'],
                        ];
                    @endphp

                    <div class="space-y-6">
                        @foreach($socialProviders as $providerKey => $providerInfo)
                        <div class="border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-{{ $providerInfo['color'] }}-100 p-1.5 rounded-lg">
                                        <svg class="w-5 h-5 text-{{ $providerInfo['color'] }}-600" viewBox="0 0 24 24">{!! $providerInfo['icon'] !!}</svg>
                                    </span>
                                    <span class="font-semibold text-gray-800">{{ $providerInfo['label'] }}</span>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <span class="text-sm text-gray-600">Enable</span>
                                    <input type="checkbox" name="{{ $providerKey }}_login_enabled" value="1"
                                        {{ ($settings["{$providerKey}_login_enabled"] ?? '0') === '1' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Client ID</label>
                                    <input type="text" name="{{ $providerKey }}_client_id"
                                        value="{{ $settings["{$providerKey}_client_id"] ?? '' }}"
                                        placeholder="Paste Client ID..."
                                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Client Secret</label>
                                    <input type="text" name="{{ $providerKey }}_client_secret"
                                        value="{{ $settings["{$providerKey}_client_secret"] ?? '' }}"
                                        placeholder="Paste Client Secret..."
                                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono">
                                </div>
                            </div>
                            <div class="mt-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-xs text-gray-400">Callback URL: <code class="bg-gray-100 px-1 rounded text-xs">{{ url('/auth/' . $providerKey . '/callback') }}</code></span>
                            </div>
                        </div>
                        @endforeach
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
