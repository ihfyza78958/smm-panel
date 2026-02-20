<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Blog - Nepalboost</title>
    <meta name="description" content="Latest news, tips, and updates about social media marketing services from Nepalboost.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 selection:bg-indigo-500 selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-indigo-200">S</div>
                    <a href="/" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700">
                        Nepalboost
                    </a>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition">Home</a>
                    <a href="{{ url('/#services') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition">Services</a>
                    <a href="{{ url('/#api') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition">API</a>
                    <a href="{{ route('blog.index') }}" class="text-indigo-600 font-bold transition">Blog</a>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-700 hover:text-indigo-600 transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="pt-32 pb-12 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Blog & News</h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">Latest tips, updates, and guides about social media marketing.</p>
        </div>
    </section>

    <!-- Blog Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    @if($post->image)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @else
                    <div class="aspect-video bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h2>
                        <p class="text-gray-500 text-sm line-clamp-3">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1 mt-4 text-indigo-600 font-semibold text-sm hover:gap-2 transition-all">
                            Read More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
            @else
            <div class="text-center py-20">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <h3 class="text-xl font-bold text-gray-400">No blog posts yet</h3>
                <p class="text-gray-400 mt-2">Check back soon for updates!</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} Nepalboost. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
