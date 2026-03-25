<x-guest-layout>
    <div class="mb-8 text-center">
        <a href="/" class="inline-flex items-center gap-2 mb-4">
            <span class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center shadow-lg shadow-slate-300">
                <svg viewBox="0 0 24 24" class="w-6 h-6" fill="white"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </span>
        </a>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">System Access</h2>
        <p class="mt-1 text-sm text-slate-500 font-medium tracking-wide uppercase">Authorized Personnel Only</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        
        <!-- We use a honeypot field to trap bots -->
        <div style="display:none;">
            <input type="text" name="admin_trap" value="">
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Command Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                class="mt-1 block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 font-mono focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition"
                placeholder="admin@system.local">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Passcode</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition"
                placeholder="••••••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        
        <div class="block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900" name="remember">
                <span class="ml-2 text-sm text-slate-600 font-medium">Retain Access</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white font-bold tracking-wide py-3 px-4 rounded-lg hover:bg-slate-800 focus:ring-4 focus:ring-slate-200 transition-all shadow-md">
            INITIALIZE SESSION
        </button>
    </form>
    
    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
        <p class="text-xs text-slate-400">IP Address: {{ request()->ip() }} recorded.</p>
    </div>
</x-guest-layout>