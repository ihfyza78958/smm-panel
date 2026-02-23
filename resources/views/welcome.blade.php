<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags -->
    <title>NepalBoost - #1 Cheapest SMM Panel for Nepal & India | Buy Followers & Likes</title>
    <meta name="description" content="Best & Cheapest SMM Panel in Nepal and India. Buy Instagram followers, TikTok views, YouTube watchtime, with instant delivery via eSewa, Khalti, IME Pay, UPI & Paytm. Wholesale SMM services for resellers.">
    <meta name="keywords" content="nepalboost, smm panel nepal, smm panel india, cheapest smm panel india, buy instagram followers nepal, tiktok views nepal, youtube watchtime india, facebook likes, esewa smm panel, khalti payment, upi smm panel, paytm smm panel, social media marketing reseller">
    <meta name="author" content="NepalBoost">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical -->
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="NepalBoost - Best & Cheapest SMM Panel for Nepal and India">
    <meta property="og:description" content="Boost your social media presence with NepalBoost. Cheapest rates for Instagram, TikTok, YouTube & Facebook. Instant delivery with local payments.">
    <meta property="og:site_name" content="NepalBoost">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NepalBoost - Best & Cheapest SMM Panel for Nepal and India">
    <meta name="twitter:description" content="Boost your social media presence with NepalBoost. Cheapest rates for Instagram, TikTok, YouTube & Facebook. Instant delivery with local payments.">
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "NepalBoost",
        "url": "{{ url('/') }}",
        "description": "Best & Cheapest SMM Panel in Nepal and India",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ url('/services') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "NepalBoost",
        "url": "{{ url('/') }}",
        "description": "Best & Cheapest SMM Panel in Nepal and India. Buy Instagram followers, TikTok views, YouTube watchtime with instant delivery.",
        "sameAs": []
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
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#6366f1 0.5px, transparent 0.5px), radial-gradient(#6366f1 0.5px, #ffffff 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-800 selection:bg-indigo-500 selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-200 group-hover:shadow-indigo-300 transition-all duration-300 group-hover:scale-105">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 4V20L8 12L12 20V4" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 4V20H18C20.2091 20 22 18.2091 22 16V16C22 13.7909 20.2091 12 18 12H14M14 12H18C20.2091 12 22 10.2091 22 8V8C22 5.79086 20.2091 4 18 4H14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-gray-900">Nepal<span class="text-indigo-600">Boost</span></span>
                        <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-[0.2em] -mt-0.5">SMM Panel</span>
                    </div>
                </a>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#services" class="text-gray-600 hover:text-indigo-600 font-medium transition">Services</a>
                    <a href="#api" class="text-gray-600 hover:text-indigo-600 font-medium transition">API</a>
                    <a href="#blog" class="text-gray-600 hover:text-indigo-600 font-medium transition">Blog</a>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-700 hover:text-indigo-600 transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 transform hover:-translate-y-0.5">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 hero-pattern -z-10"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-indigo-50 via-purple-50 to-transparent -z-20 opacity-60"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-6">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> #1 Top Rated Panel
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
                        Viral Growth <br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Made Simple.</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                        The cheapest SMM Panel in Nepal for Instagram, TikTok, & YouTube growth. Automated delivery 24/7.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-indigo-700 transition shadow-xl shadow-indigo-200">
                            Get Started Free
                        </a>
                        <a href="#services" class="bg-white text-gray-700 border border-gray-200 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-50 transition">
                            View Services
                        </a>
                    </div>

                    <div class="mt-12 flex items-center justify-center lg:justify-start gap-8 opacity-70 grayscale hover:grayscale-0 transition duration-500">
                       <img src="https://upload.wikimedia.org/wikipedia/commons/e/e7/Instagram_logo_2016.svg" class="h-8">
                       <img src="https://upload.wikimedia.org/wikipedia/commons/0/09/YouTube_full-color_icon_%282017%29.svg" class="h-6">
                       <img src="https://upload.wikimedia.org/wikipedia/en/a/a9/TikTok_logo.svg" class="h-8">
                       <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" class="h-8">
                    </div>
                </div>

                <!-- Right Content (Login Form / Image) -->
                <div class="relative">
                    <div class="relative max-w-md mx-auto lg:ml-auto">
                        <!-- 3D Rocket Image -->
                        <img src="{{ asset('hero-rocket.png') }}" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[150%] max-w-none h-auto z-0 opacity-20 lg:opacity-100 pointer-events-none animate-blob" alt="Social Media Growth">

                        <!-- Login Card -->
                        @guest
                        <div class="relative z-10 glass-panel p-8 rounded-3xl shadow-2xl border-t border-white">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Member Login</h3>
                            <p class="text-gray-500 mb-6 text-sm">Access your dashboard to place orders.</p>
                            
                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-white/50" placeholder="you@example.com" required>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-sm font-medium text-gray-700">Password</label>
                                        <a href="#" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Forgot?</a>
                                    </div>
                                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-white/50" placeholder="••••••••" required>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 transform active:scale-95">
                                    Sign In
                                </button>
                            </form>
                            <div class="mt-6 text-center text-sm text-gray-500">
                                Don't have an account? <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Sign up</a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="relative z-10 glass-panel p-8 rounded-3xl shadow-2xl max-w-md mx-auto lg:ml-auto border-t border-white text-center">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back!</h3>
                        <p class="text-gray-500 mb-6">You are already logged in as <strong>{{ auth()->user()->name }}</strong>.</p>
                        <a href="{{ route('dashboard') }}" class="block w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Go to Dashboard</a>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-10 bg-indigo-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center divide-x divide-indigo-800/50">
                <div>
                    <div class="text-4xl font-bold mb-1">1.2M+</div>
                    <div class="text-indigo-200 text-sm font-medium uppercase tracking-wide">Orders Completed</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-1">0.02s</div>
                    <div class="text-indigo-200 text-sm font-medium uppercase tracking-wide">Speed</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-1">5yrs</div>
                    <div class="text-indigo-200 text-sm font-medium uppercase tracking-wide">Experience</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-1">24/7</div>
                    <div class="text-indigo-200 text-sm font-medium uppercase tracking-wide">Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content / SEO Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">What is SMM Panel Nepal?</h2>
                    <div class="prose prose-indigo text-gray-600 leading-relaxed space-y-4">
                        <p class="text-lg text-gray-600 leading-relaxed mb-6">
                            <strong>NepalBoost</strong> is an online social media marketing tool (SMM Panel) that allows individuals and businesses to buy likes, followers, and views for various social media platforms such as Instagram, Facebook, TikTok, YouTube, and Twitter.
                        </p>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            We are known as the <span class="text-indigo-600 font-bold">Cheapest SMM Panel in Nepal</span> because we connect directly with main providers to offer wholesale rates. Whether you are an influencer looking to boost your reach or a brand trying to establish credibility, our services are designed to help you grow.
                        </p>
                        <p>
                            With detailed API documentation, we are also the perfect choice for <strong>Resellers</strong> who want to start their own SMM agency. We support local payment methods mainly <strong>eSewa, Khalti, and IME Pay</strong>, making it accessible for everyone in Nepal.
                        </p>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Why we are the Best?</h2>
                    <ul class="space-y-6">
                        <li class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Unbeatable Prices</h4>
                                <p class="text-gray-600 text-sm">We guarantee the lowest prices in the market starting from NPR 10.</p>
                            </div>
                        </li>
                        <li class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Instant Delivery</h4>
                                <p class="text-gray-600 text-sm">99% of our services start instantly. No more waiting.</p>
                            </div>
                        </li>
                        <li class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">High Quality Profiles</h4>
                                <p class="text-gray-600 text-sm">We provide real-looking followers with profile pictures and posts.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Table (Expandable) -->
    <div id="services" class="py-24 bg-gray-50 border-t border-gray-200" x-data="{ expanded: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm">Pricing</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Unbeatable Service Rates</h2>
                <p class="text-gray-500 mt-4 max-w-2xl mx-auto">Browse our wholesale prices for social media services. We offer the cheapest rates in Nepal & India.</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Service</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Price / 1k</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Min Order</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Loop through categories and services, but limit visual display via Alpine -->
                        @foreach($categories as $index => $category)
                            @foreach($category->services->take(3) as $service)
                            <tr class="hover:bg-indigo-50/30 transition" x-show="expanded || {{ $index }} < 3">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                    <div class="text-xs text-indigo-500">{{ $category->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        NPR {{ number_format($service->price, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ $service->min_quantity }}
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                <!-- Expand/Collapse Button -->
                <div class="p-4 bg-gray-50 text-center border-t border-gray-200">
                    <button @click="expanded = !expanded" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 focus:outline-none">
                        <span x-text="expanded ? 'Show Less Services' : 'View All 1000+ Services'"></span>
                        <span x-show="!expanded">&rarr;</span>
                        <span x-show="expanded">&uarr;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Section -->
    @if(isset($latest_posts) && $latest_posts->count() > 0)
    <section id="blog" class="py-20 bg-white" x-data="{ expanded: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm">Our Blog</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Latest Updates & Tips</h2>
                <p class="text-gray-500 mt-4 max-w-2xl mx-auto">Stay updated with the latest social media trends and platform updates.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                @foreach($latest_posts as $index => $post)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition group" 
                     x-show="expanded || {{ $index }} < 3">
                    @if($post->image)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        </div>
                    @else
                        <div class="h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="text-xs font-bold text-indigo-600 mb-2 uppercase tracking-wide">{{ $post->created_at->format('M d, Y') }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition">{{ $post->title }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800">
                            Read Article 
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($latest_posts->count() > 3)
            <div class="text-center">
                <button @click="expanded = !expanded" class="inline-flex items-center justify-center px-6 py-3 border border-indigo-600 text-indigo-600 rounded-full font-bold hover:bg-indigo-50 transition">
                    <span x-text="expanded ? 'Show Less Posts' : 'View All Posts'"></span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!expanded"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="expanded"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Features / SEO Content Block -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="p-6 rounded-2xl bg-indigo-50 border border-indigo-100">
                    <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center text-white mb-4 mx-auto md:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Reseller Friendly</h3>
                    <p class="text-gray-600 text-sm">start your own SMM business with our easy-to-use API. We provide wholesale rates so you can resell and make profit.</p>
                </div>
                <div class="p-6 rounded-2xl bg-purple-50 border border-purple-100">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center text-white mb-4 mx-auto md:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Local Payments</h3>
                    <p class="text-gray-600 text-sm">We accept eSewa, Khalti, IME Pay, Prabhu Pay, UPI (India), and Paytm. No credit card required.</p>
                </div>
                <div class="p-6 rounded-2xl bg-green-50 border border-green-100">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center text-white mb-4 mx-auto md:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Secure & Safe</h3>
                    <p class="text-gray-600 text-sm">Your privacy is our priority. We never ask for your passwords. All services are safe for your accounts.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="prose prose-indigo max-w-none text-gray-600">
                    <h2 class="text-3xl font-bold text-gray-900">Why choose us as your SMM Panel?</h2>
                    <p>
                        NepalBoost is not just another panel; we are a dedicated team of marketing experts aiming to provide the <strong>cheapest and most effective social media marketing services</strong> in South Asia. Whether you are from Kathmandu, Delhi, Mumbai, or Pokhara, our services are tailored to boost your digital presence.
                    </p>
                    <p>
                        We understand the algorithm of Instagram, TikTok, and YouTube. Our views and likes are designed to help your content go viral. With over 5 years of experience, we have processed millions of orders for influencers, brands, and celebrities.
                    </p>
                    <h3 class="text-xl font-bold text-gray-900 mt-6">Features of our Panel:</h3>
                    <ul class="list-disc pl-5 space-y-2 mt-4">
                        <li><strong>Instant Delivery:</strong> Our automated system processes orders within seconds.</li>
                        <li><strong>24/7 Support:</strong> We have a dedicated support team available via Ticket and WhatsApp.</li>
                        <li><strong>API for Developers:</strong> Easy integration for panel owners.</li>
                        <li><strong>Drip Feed:</strong> Build growth naturally over time.</li>
                    </ul>
                </div>
                
                <div class="relative">
                    <div class="absolute inset-0 bg-indigo-100 rounded-3xl transform rotate-3"></div>
                    <div class="relative bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h3>
                        <div x-data="{ active: null }" class="space-y-4">
                            <!-- FAQ 1 -->
                            <div class="border-b border-gray-100 pb-4">
                                <button @click="active = (active === 1 ? null : 1)" class="flex justify-between items-center w-full text-left font-semibold text-gray-800 hover:text-indigo-600 transition">
                                    <span>What is an SMM Panel?</span>
                                    <svg class="w-5 h-5 transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="active === 1" x-collapse class="mt-2 text-sm text-gray-600">
                                    An SMM (Social Media Marketing) Panel is an online shop where you can buy cheap social media services like followers, likes, and views.
                                </div>
                            </div>
                            <!-- FAQ 2 -->
                            <div class="border-b border-gray-100 pb-4">
                                <button @click="active = (active === 2 ? null : 2)" class="flex justify-between items-center w-full text-left font-semibold text-gray-800 hover:text-indigo-600 transition">
                                    <span>Is it safe for my account?</span>
                                    <svg class="w-5 h-5 transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="active === 2" x-collapse class="mt-2 text-sm text-gray-600">
                                    Yes, absolutely. We use high-quality profiles and safe methods to deliver services. We never ask for your password.
                                </div>
                            </div>
                             <!-- FAQ 3 -->
                            <div class="border-b border-gray-100 pb-4">
                                <button @click="active = (active === 3 ? null : 3)" class="flex justify-between items-center w-full text-left font-semibold text-gray-800 hover:text-indigo-600 transition">
                                    <span>How can I add funds?</span>
                                    <svg class="w-5 h-5 transition-transform" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="active === 3" x-collapse class="mt-2 text-sm text-gray-600">
                                    You can add funds via <strong>eSewa, Khalti, IME Pay</strong> in Nepal. For international users, we accept Binance, Payeer, and Perfect Money. Indian users can use <strong>UPI/Paytm</strong>.
                                </div>
                            </div>
                            <!-- FAQ 4 -->
                            <div class="border-b border-gray-100 pb-4">
                                <button @click="active = (active === 4 ? null : 4)" class="flex justify-between items-center w-full text-left font-semibold text-gray-800 hover:text-indigo-600 transition">
                                    <span>Do you offer support?</span>
                                    <svg class="w-5 h-5 transition-transform" :class="active === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="active === 4" x-collapse class="mt-2 text-sm text-gray-600">
                                    Yes, we have 24/7 customer support. You can open a ticket from your dashboard or contact us via WhatsApp for faster resolution.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <div class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-purple-200 mb-6 drop-shadow-sm">
                Ready to Go Viral?
            </h2>
            <p class="text-indigo-100 text-xl mb-12 max-w-2xl mx-auto leading-relaxed">
                Join the fastest growing SMM network in Nepal. Instant delivery, wholesale prices, and 24/7 support.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-900 rounded-full font-bold text-lg hover:bg-indigo-50 transition transform hover:-translate-y-1 shadow-xl shadow-indigo-900/50">
                    Get Started Now
                </a>
                <a href="#services" class="px-8 py-4 bg-transparent border-2 border-indigo-400 text-white rounded-full font-bold text-lg hover:bg-indigo-900/50 transition backdrop-blur-sm">
                    View Pricing
                </a>
            </div>
        </div>
    </div>

    <!-- Modern Dark Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 pt-20 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                         <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-900/20">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 4V20L8 12L12 20V4" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 4V20H18C20.2091 20 22 18.2091 22 16V16C22 13.7909 20.2091 12 18 12H14M14 12H18C20.2091 12 22 10.2091 22 8V8C22 5.79086 20.2091 4 18 4H14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                         </div>
                        <span class="text-2xl font-bold text-white">Nepal<span class="text-indigo-400">Boost</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm mb-6">
                        The trusted partner for influencers, brands, and agencies. We provide the cheapest and most reliable social media services in Nepal & India.
                    </p>
                    <div class="flex gap-4">
                        <!-- Facebook -->
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <!-- Twitter -->
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-blue-400 hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <!-- Instagram -->
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-pink-600 hover:text-white transition">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.06 1.265.072 1.645.072 4.849 0 3.205-.012 3.584-.072 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.06-1.644.072-4.85.072-3.205 0-3.584-.012-4.85-.072-3.225-.148-4.771-1.664-4.919-4.919-.06-1.265-.072-1.644-.072-4.849 0-3.204.012-3.584.072-4.849.149-3.225 1.664-4.771 4.919-4.919 1.266-.06 1.645-.072 4.85-.072zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Company</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="https://nepalboost.com" class="hover:text-indigo-400 transition">NepalBoost IT</a></li>
                        <li><a href="#services" class="hover:text-indigo-400 transition">Our Services</a></li>
                        <li><a href="#blog" class="hover:text-indigo-400 transition">Blog / News</a></li>
                        <li><a href="https://nepalboost.com#contact" class="hover:text-indigo-400 transition">Contact Support</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Legal & Help</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="{{ route('page.terms') }}" class="hover:text-indigo-400 transition">Terms of Service</a></li>
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-indigo-400 transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">Refund Policy</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">API Documentation</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} NepalBoost. All rights reserved.</p>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>Made with</span>
                    <svg class="w-4 h-4 text-red-500 fill-current animate-pulse" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span>in Kathmandu</span>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .animate-blob { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
    </style>
</body>
</html>
