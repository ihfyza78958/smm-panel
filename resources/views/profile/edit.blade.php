<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- API Key Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">API Access</h2>
                    <p class="mt-1 text-sm text-gray-600">Use your API key to place orders programmatically.</p>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your API Key</label>
                        <div class="flex gap-2">
                            <input type="text" value="{{ auth()->user()->api_key ?? 'Not generated yet' }}" readonly
                                class="flex-1 rounded-lg border-gray-300 bg-gray-50 font-mono text-sm">
                            <form method="POST" action="{{ route('profile.api-key') }}">
                                @csrf
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium whitespace-nowrap">
                                    {{ auth()->user()->api_key ? 'Regenerate' : 'Generate' }}
                                </button>
                            </form>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">API Endpoint: <code class="bg-gray-100 px-1 py-0.5 rounded">{{ url('/api/v2') }}</code></p>
                    </div>

                    @if(auth()->user()->api_key)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-800 mb-2">Quick API Example</h3>
                        <pre class="text-xs text-gray-600 overflow-x-auto"><code>curl -X POST {{ url('/api/v2') }} \
  -d "key={{ auth()->user()->api_key }}" \
  -d "action=services"</code></pre>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
