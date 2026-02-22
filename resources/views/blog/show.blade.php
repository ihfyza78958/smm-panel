<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags -->
    <title>{{ $post->title }} - Nepalboost</title>
    <meta name="description" content="{{ $post->excerpt }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('blog.show', $post->slug) }}">
    
    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt }}">
    <meta property="og:site_name" content="Nepalboost">
    @if($post->featured_image)
    <meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}">
    @endif
    <meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ $post->excerpt }}">
    @if($post->featured_image)
    <meta name="twitter:image" content="{{ asset('storage/' . $post->featured_image) }}">
    @endif
    
    <!-- JSON-LD Article -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Article",
        "headline": "{{ $post->title }}",
        "description": "{{ $post->excerpt }}",
        "url": "{{ route('blog.show', $post->slug) }}",
        @if($post->featured_image)
        "image": "{{ asset('storage/' . $post->featured_image) }}",
        @endif
        "datePublished": "{{ $post->created_at->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "author": {
            "@@type": "Organization",
            "name": "Nepalboost"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "Nepalboost",
            "url": "{{ url('/') }}"
        },
        "mainEntityOfPage": {
            "@@type": "WebPage",
            "@@id": "{{ route('blog.show', $post->slug) }}"
        }
    }
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .prose img {
            border-radius: 0.75rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 selection:bg-indigo-500 selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-indigo-200">S</div>
                    <a href="/" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700">
                        Nepalboost
                    </a>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ url('/#services') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition">Services</a>
                    <a href="{{ url('/#api') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition">API</a>
                    <a href="{{ url('/#blog') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition">Blog</a>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-700 hover:text-indigo-600 transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 transform hover:-translate-y-0.5">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Content Section -->
    <section class="pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 text-center">
                <a href="{{ url('/#blog') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Blog
                </a>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">{{ $post->title }}</h1>
                
                <div class="flex items-center justify-center gap-4 text-sm text-gray-500 mb-8">
                     <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full font-bold text-xs uppercase">{{ $post->created_at->format('M d, Y') }}</span>
                     <span>•</span>
                     <span>By Nepalboost</span>
                </div>
            </div>

            @if($post->image)
            <div class="mb-12 rounded-2xl overflow-hidden shadow-2xl shadow-indigo-100">
                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
            </div>
            @endif

            <div class="prose prose-lg prose-indigo mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- Share / CTA -->
            <div class="mt-12 bg-indigo-900 rounded-2xl p-8 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(#6366f1_1px,transparent_1px)] [background-size:16px_16px] opacity-20"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-4">Ready to boost your social media?</h3>
                    <p class="text-indigo-200 mb-6">Get started with the cheapest SMM panel in Nepal today.</p>
                    <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-900 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition shadow-lg">Create Free Account</a>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
               <div class="col-span-2">
                   <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">S</div>
                       <span class="text-xl font-bold text-gray-900">Nepalboost</span>
                   </div>
                   <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                       The #1 Cheapest SMM Panel in Nepal. We provide high-quality social media marketing services for Instagram, Facebook, TikTok, YouTube, and more.
                   </p>
               </div>
               <div>
                   <h4 class="font-bold text-gray-900 mb-4">Quick Links</h4>
                   <ul class="space-y-2 text-sm text-gray-600">
                       <li><a href="{{ url('/') }}" class="hover:text-indigo-600">Home</a></li>
                       <li><a href="{{ url('/#services') }}" class="hover:text-indigo-600">Services</a></li>
                       <li><a href="{{ route('login') }}" class="hover:text-indigo-600">Login</a></li>
                       <li><a href="{{ route('register') }}" class="hover:text-indigo-600">Sign Up</a></li>
                   </ul>
               </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-indigo-600">FAQ</a></li>
                        <li><a href="#" class="hover:text-indigo-600">API Docs</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Contact Us</a></li>
                        <li><a href="{{ route('page.terms') }}" class="hover:text-indigo-600">Terms of Service</a></li>
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-indigo-600">Privacy Policy</a></li>
                    </ul>
                </div>
           </div>
           <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
               <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Nepalboost. All rights reserved.</p>
           </div>
       </div>
   </footer>

</body>
</html>
