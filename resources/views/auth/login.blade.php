<x-guest-layout>
    <div class="mb-8 text-center">
        <a href="/" class="inline-flex items-center gap-2 mb-6">
            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center shadow-lg shadow-emerald-200">
                <svg viewBox="0 0 16 24" class="w-5 h-6" fill="white"><path d="M4 4V20L12 4V20"/></svg>
            </span>
            <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700">NepalBoost</span>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
        <p class="mt-2 text-sm text-gray-600">Sign in to your SMM dashboard</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>

        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
            Sign In
        </button>

        <div class="relative flex items-center justify-center mt-6">
            <div class="border-t border-gray-200 w-full absolute"></div>
            <span class="bg-white px-3 text-xs text-gray-500 relative font-medium uppercase">Or continue with</span>
        </div>

        @php
            $socialProviders = [
                'google'   => ['label' => 'Google',   'enabled_key' => 'google_login_enabled',   'icon' => 'M12.0003 20.45c4.656 0 8.556-3.21 9.9-7.59h-9.9v-4.14h15.24c.21 1.14.33 2.31.33 3.51 0 7.8-5.37 13.53-12.93 13.53-7.23 0-13.08-5.85-13.08-13.08S5.34 2.67 12.0003 2.67c3.48 0 6.63 1.26 9.06 3.54l-3.21 3.21c-1.5-1.44-3.51-2.34-5.85-2.34-4.8 0-8.79 3.66-10.26 8.28-1.2 3.75 1.62 7.74 5.46 8.94.99.3 2.04.45 3.09.45z'],
                'github'   => ['label' => 'GitHub',   'enabled_key' => 'github_login_enabled',   'icon' => 'M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.009-.868-.013-1.703-2.782.605-3.369-1.34-3.369-1.34-.454-1.154-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.741 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z'],
                'facebook' => ['label' => 'Facebook', 'enabled_key' => 'facebook_login_enabled', 'icon' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
            ];
        @endphp

        @foreach($socialProviders as $providerKey => $providerInfo)
            @if(\App\Models\Setting::get($providerInfo['enabled_key'], '0') === '1')
            <a href="{{ route('auth.social', ['provider' => $providerKey]) }}"
                class="w-full flex items-center justify-center gap-3 py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-200 mt-3">
                <svg class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24"><path d="{{ $providerInfo['icon'] }}" fill="currentColor"/></svg>
                Sign in with {{ $providerInfo['label'] }}
            </a>
            @endif
        @endforeach
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Create one now</a>
        </p>
    </div>
</x-guest-layout>
