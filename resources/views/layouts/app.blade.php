<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nepalboost - Dashboard</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden fixed top-0 w-full z-20 h-16 transition-all duration-300">
        <div class="px-4 h-full flex justify-between items-center">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-indigo-600 focus:outline-none p-1 rounded-md hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex flex-col">
                    <span class="font-bold text-lg leading-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Nepalboost</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold border border-indigo-100 shadow-sm">NPR</div>
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random" class="w-9 h-9 rounded-full border-2 border-white shadow-sm">
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 bg-white border-r border-gray-200 w-64 transform transition-transform duration-300 lg:relative lg:translate-x-0 z-30 flex flex-col h-full shadow-lg lg:shadow-none"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-100 bg-white">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-110 transition-transform">S</div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-600 group-hover:from-indigo-600 group-hover:to-purple-600 transition-all">Nepalboost</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">Dashboard</x-nav-link>
            <x-nav-link href="{{ route('orders.new') }}" :active="request()->routeIs('orders.new')" icon="plus-circle">New Order</x-nav-link>
            <x-nav-link href="{{ route('orders.history') }}" :active="request()->routeIs('orders.history')" icon="shopping-bag">Orders</x-nav-link>
            <x-nav-link href="{{ route('wallet.index') }}" :active="request()->routeIs('wallet.index')" icon="credit-card">Add Funds</x-nav-link>
            <x-nav-link href="{{ route('tickets.index') }}" :active="request()->routeIs('tickets.*')" icon="life-buoy">Support</x-nav-link>
            <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')" icon="user">Profile</x-nav-link>
        </nav>

        <!-- User Profile Simplified -->
        <div class="border-t border-gray-100 p-4 bg-gray-50/50">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" class="w-10 h-10 rounded-full border-2 border-white shadow-sm hover:scale-105 transition-transform cursor-pointer">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-indigo-600 font-medium truncate">Bal: NPR {{ number_format(auth()->user()->balance, 2) }}</p>
                </div>
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-20 lg:hidden" x-cloak></div>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-50 pt-16 lg:pt-0 relative w-full">
        <!-- Desktop Header -->
        <div class="hidden lg:flex justify-between items-center h-16 bg-white/80 backdrop-blur-md border-b border-gray-200 px-8 sticky top-0 z-10 transition-all">
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-4">
                <!-- Notifications Bell -->
                <button class="relative p-2 text-gray-400 hover:text-gray-500">
                    <span class="absolute top-2 right-2 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                
                <div class="flex items-center gap-2 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">
                    <span class="text-xs font-bold text-indigo-600 tracking-wide">BALANCE</span>
                    <span class="text-sm font-extrabold text-indigo-800">NPR {{ number_format(auth()->user()->balance, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </main>
    
    <!-- Component Scripts -->
    @stack('scripts')
</body>
</html>
