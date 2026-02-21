<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $header ?? 'Dashboard' }} — Nepalboost Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 transform flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
        
        <!-- Brand -->
        <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-cyan-500 rounded-lg flex items-center justify-center text-white text-xs font-black">NB</div>
                <span class="text-lg font-bold tracking-wide">Nepal<span class="text-emerald-400">boost</span></span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 no-scrollbar">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mb-2">Overview</div>
            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="navigator" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Dashboard</x-nav-link>
            
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mt-6 mb-2">Management</div>
            <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" icon="users" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Users</x-nav-link>
            <x-nav-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')" icon="shopping-bag" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Orders</x-nav-link>
            <x-nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')" icon="grid" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Categories</x-nav-link>
            <x-nav-link href="{{ route('admin.services.index') }}" :active="request()->routeIs('admin.services.*')" icon="layers" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Services</x-nav-link>
            <x-nav-link href="{{ route('admin.providers.index') }}" :active="request()->routeIs('admin.providers.*')" icon="server" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Providers</x-nav-link>

            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mt-6 mb-2">Marketing</div>
            <x-nav-link href="{{ route('admin.coupons.index') }}" :active="request()->routeIs('admin.coupons.*')" icon="tag" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Coupons</x-nav-link>
            <x-nav-link href="{{ route('admin.announcements.index') }}" :active="request()->routeIs('admin.announcements.*')" icon="bell" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Announcements</x-nav-link>
            <x-nav-link href="{{ route('admin.blogs.index') }}" :active="request()->routeIs('admin.blogs.*')" icon="book-open" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Blog & News</x-nav-link>

            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mt-6 mb-2">Support</div>
            <x-nav-link href="{{ route('admin.tickets.index') }}" :active="request()->routeIs('admin.tickets.*')" icon="life-buoy" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Tickets</x-nav-link>
            <x-nav-link href="{{ route('admin.transactions.index') }}" :active="request()->routeIs('admin.transactions.*')" icon="credit-card" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Transactions</x-nav-link>
            
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mt-6 mb-2">System</div>
            <x-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')" icon="settings" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Settings</x-nav-link>
            <x-nav-link href="{{ route('admin.logs.index') }}" :active="request()->routeIs('admin.logs.*')" icon="activity" class="!text-slate-300 hover:!bg-slate-800 hover:!text-white">Activity Logs</x-nav-link>
        </nav>

        <!-- Admin Profile -->
        <div class="p-4 bg-slate-950 border-t border-slate-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-xs font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-slate-400 hover:text-white transition" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="transition-all duration-300 lg:ml-64">
        
        <!-- Top Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex justify-between items-center px-4 sm:px-6 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-emerald-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>
                <div class="hidden sm:flex items-center text-sm text-gray-500">
                    <span class="px-2">/</span>
                    <span class="font-medium text-gray-700">{{ $header ?? 'Dashboard' }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                    $pendingTransactions = \App\Models\Transaction::where('status', 'pending')->count();
                    $openTickets = \App\Models\Ticket::where('status', 'open')->count();
                @endphp
                @if($pendingOrders + $pendingTransactions + $openTickets > 0)
                <div class="hidden md:flex items-center gap-3 text-xs">
                    @if($pendingOrders > 0)
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-2.5 py-1.5 rounded-full border border-yellow-200 hover:bg-yellow-100 transition">
                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                        {{ $pendingOrders }} pending orders
                    </a>
                    @endif
                    @if($pendingTransactions > 0)
                    <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-1.5 bg-orange-50 text-orange-700 px-2.5 py-1.5 rounded-full border border-orange-200 hover:bg-orange-100 transition">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        {{ $pendingTransactions }} pending txns
                    </a>
                    @endif
                    @if($openTickets > 0)
                    <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-full border border-blue-200 hover:bg-blue-100 transition">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        {{ $openTickets }} open tickets
                    </a>
                    @endif
                </div>
                @else
                <div class="hidden md:flex items-center gap-2 bg-green-50 px-3 py-1.5 rounded-full border border-green-100">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="text-xs font-semibold text-green-700">All Clear</span>
                </div>
                @endif
            </div>
        </header>

        <!-- Content Body -->
        <div class="p-4 sm:p-6">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-3" x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-400 hover:text-green-600">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-3" x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-400 hover:text-red-600">&times;</button>
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-sm text-yellow-700 flex items-center gap-3" x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.928-.833-2.598 0L3.206 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    <span class="flex-1">{{ session('warning') }}</span>
                    <button @click="show = false" class="text-yellow-400 hover:text-yellow-600">&times;</button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>
</body>
</html>
