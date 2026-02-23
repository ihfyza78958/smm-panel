<x-app-layout>
    <x-slot name="header">Profile & API</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Profile & Password -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Profile Information</h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Update Password</h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-red-100 flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-red-50 text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h3 class="font-bold text-red-700">Danger Zone</h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

        <!-- Right Column: API Access -->
        <div class="space-y-6">
            <!-- API Key Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <h3 class="font-bold text-gray-900 text-sm">API Access</h3>
                </div>
                <div class="p-5">
                    <p class="text-xs text-gray-500 mb-4">Use your API key to place orders programmatically via our REST API.</p>

                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Your API Key</label>
                    <div class="flex gap-2">
                        <input type="text" value="{{ auth()->user()->api_key ?? 'Not generated yet' }}" readonly
                            class="flex-1 rounded-lg border-gray-200 bg-gray-50 font-mono text-xs focus:ring-emerald-500 focus:border-emerald-500">
                        <form method="POST" action="{{ route('profile.api-key') }}">
                            @csrf
                            <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition text-xs font-bold whitespace-nowrap">
                                {{ auth()->user()->api_key ? 'Regenerate' : 'Generate' }}
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Endpoint</span>
                            <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 font-mono text-[10px]">{{ url('/api/v2') }}</code>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Method</span>
                            <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 font-mono text-[10px]">POST</code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 text-sm">Available Actions</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach([
                        ['services', 'List all services', 'GET'],
                        ['add', 'Place a new order', 'POST'],
                        ['status', 'Check order status', 'GET'],
                        ['balance', 'Check account balance', 'GET'],
                    ] as $action)
                    <div class="px-5 py-3 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $action[0] }}</p>
                            <p class="text-[10px] text-gray-400">{{ $action[1] }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-600">{{ $action[2] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            @if(auth()->user()->api_key)
            <!-- Quick Example -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    Quick Example
                </h3>
                <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-[11px] text-green-400 font-mono leading-relaxed"><code>curl -X POST {{ url('/api/v2') }} \
  -d "key={{ auth()->user()->api_key }}" \
  -d "action=services"</code></pre>
                </div>
                <p class="text-[10px] text-gray-400 mt-2 text-center">Returns JSON list of all available services</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
