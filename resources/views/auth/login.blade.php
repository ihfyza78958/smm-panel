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

        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
            <svg class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24">
                <path d="M12.0003 20.45c4.656 0 8.556-3.21 9.9-7.59h-9.9v-4.14h15.24c.21 1.14.33 2.31.33 3.51 0 7.8-5.37 13.53-12.93 13.53-7.23 0-13.08-5.85-13.08-13.08S5.34 2.67 12.0003 2.67c3.48 0 6.63 1.26 9.06 3.54l-3.21 3.21c-1.5-1.44-3.51-2.34-5.85-2.34-4.8 0-8.79 3.66-10.26 8.28-1.2 3.75 1.62 7.74 5.46 8.94.99.3 2.04.45 3.09.45z" fill="currentColor" />
            </svg>
            Sign in with Google
        </a>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Create one now</a>
        </p>
    </div>
</x-guest-layout>
