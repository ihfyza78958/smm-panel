<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMM Nepal Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100/50 text-gray-800" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 transform"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-64'">
        
        <!-- Brand -->
        <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800">
            <span class="text-xl font-bold tracking-wide">SMM <span class="text-indigo-400">ADMIN</span></span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
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
        <div class="p-4 bg-slate-950 border-t border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-xs font-bold">AD</div>
                <div class="flex-1">
                    <p class="text-sm font-medium">Administrator</p>
                    <p class="text-xs text-slate-500">Super Admin</p>
                </div>
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : ''">
        
        <!-- Top Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex justify-between items-center px-6 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-indigo-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>
                <div class="hidden md:flex items-center text-sm text-gray-500">
                    <span class="px-2">/</span>
                    <span class="font-medium text-gray-700">{{ $header ?? 'Dashboard' }}</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-semibold text-gray-600">System Healthy</span>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="p-6">
            {{ $slot }}
        </div>
    </main>

</body>
</html>
