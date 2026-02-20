<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>Nepalboost - IT Solutions | SMM Panel | Web & App Development | Nepal</title>
    <meta name="description" content="Nepalboost is a leading IT solutions company in Nepal. We build websites, mobile apps, provide SMM panel services, and automate your business workflows. Transform your digital presence.">
    <meta name="keywords" content="nepalboost, IT company nepal, web development nepal, mobile app development nepal, smm panel nepal, software company kathmandu, workflow automation, social media marketing">
    <meta name="author" content="Nepalboost">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Nepalboost - IT Solutions & SMM Panel | Nepal">
    <meta property="og:description" content="We build digital products that scale. Websites, apps, SMM panels, and automation tools — all from Kathmandu.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --nb-emerald: #10b981;
            --nb-emerald-light: #34d399;
            --nb-emerald-dark: #059669;
            --nb-slate: #0f172a;
            --nb-slate-light: #1e293b;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Gradient mesh background */
        .hero-mesh {
            background:
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(16,185,129,0.15), transparent),
                radial-gradient(ellipse 60% 40% at 80% 20%, rgba(6,182,212,0.1), transparent),
                radial-gradient(ellipse 50% 60% at 50% 80%, rgba(139,92,246,0.08), transparent);
        }

        /* Grid pattern */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(16,185,129,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16,185,129,0.06) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Glow effects */
        .glow-emerald { box-shadow: 0 0 60px rgba(16,185,129,0.15); }
        .glow-text { text-shadow: 0 0 40px rgba(16,185,129,0.3); }

        /* Scroll animations */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Floating animation */
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        @keyframes float-delay { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes spin-slow { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes pulse-glow { 0%,100% { opacity: 0.4; } 50% { opacity: 1; } }
        .float { animation: float 6s ease-in-out infinite; }
        .float-delay { animation: float-delay 5s ease-in-out infinite 1s; }
        .spin-slow { animation: spin-slow 20s linear infinite; }
        .pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }

        /* Card hover */
        .service-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
        }

        /* Counter animation */
        .counter { font-variant-numeric: tabular-nums; }

        /* Gradient border */
        .gradient-border {
            position: relative;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(135deg, #10b981, #06b6d4, #8b5cf6);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-800 selection:bg-emerald-500 selection:text-white overflow-x-hidden" x-data="{ mobileMenu: false }">

    {{-- ============================================= --}}
    {{-- NAVBAR --}}
    {{-- ============================================= --}}
    <nav class="fixed w-full z-50 transition-all duration-500 bg-white/80 backdrop-blur-xl border-b border-gray-100/80" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 50" :class="scrolled ? 'shadow-lg shadow-gray-100/50' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3 group">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-emerald-200/50 group-hover:shadow-emerald-300/60 transition-all duration-300 group-hover:scale-105">
                            {{-- NB Monogram --}}
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M4 4V20L8 12L12 20V4" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 4V20H18C20.2091 20 22 18.2091 22 16V16C22 13.7909 20.2091 12 18 12H14M14 12H18C20.2091 12 22 10.2091 22 8V8C22 5.79086 20.2091 4 18 4H14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-gray-900">Nepal<span class="text-emerald-600">boost</span></span>
                        <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-[0.2em] -mt-0.5">IT Solutions</span>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden lg:flex items-center gap-1">
                    {{-- Services Dropdown --}}
                    <div class="relative" x-data="{ servicesOpen: false }" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                        <button @click="servicesOpen = !servicesOpen" class="flex items-center gap-1 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">
                            Services
                            <svg class="w-4 h-4 transition-transform" :class="servicesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="servicesOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute top-full left-0 mt-1 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 p-3 z-50">
                            <a href="/smm" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition group">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                                <div><div class="text-sm font-bold text-gray-900 group-hover:text-emerald-600">SMM Panel</div><div class="text-xs text-gray-400">Boost social media instantly</div></div>
                            </a>
                            <a href="#services" @click="servicesOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-violet-50 transition group">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg></div>
                                <div><div class="text-sm font-bold text-gray-900 group-hover:text-violet-600">Web Development</div><div class="text-xs text-gray-400">Websites & SaaS platforms</div></div>
                            </a>
                            <a href="#services" @click="servicesOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-50 transition group">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
                                <div><div class="text-sm font-bold text-gray-900 group-hover:text-blue-600">Mobile Apps</div><div class="text-xs text-gray-400">iOS & Android development</div></div>
                            </a>
                            <a href="#services" @click="servicesOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-50 transition group">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                <div><div class="text-sm font-bold text-gray-900 group-hover:text-amber-600">Automation</div><div class="text-xs text-gray-400">Workflows, bots & integrations</div></div>
                            </a>
                            <a href="#services" @click="servicesOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-rose-50 transition group">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg></div>
                                <div><div class="text-sm font-bold text-gray-900 group-hover:text-rose-600">Custom Software</div><div class="text-xs text-gray-400">SaaS, ERP & dashboards</div></div>
                            </a>
                            <a href="#services" @click="servicesOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-teal-50 transition group">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-green-500 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                                <div><div class="text-sm font-bold text-gray-900 group-hover:text-teal-600">Digital Marketing</div><div class="text-xs text-gray-400">SEO, ads & growth strategy</div></div>
                            </a>
                        </div>
                    </div>

                    <a href="#work" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">Our Work</a>
                    <a href="#about" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">About</a>
                    <a href="#contact" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">Contact</a>
                    <div class="w-px h-6 bg-gray-200 mx-2"></div>
                    <a href="#contact" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-200/50 hover:shadow-emerald-300/60 hover:-translate-y-0.5">Get a Quote</a>
                </div>

                {{-- Mobile menu button --}}
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg x-show="!mobileMenu" class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenu" x-cloak class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="lg:hidden bg-white border-t border-gray-100 shadow-xl">
            <div class="px-4 py-6 space-y-2">
                {{-- Services sub-menu --}}
                <div x-data="{ servicesOpen: false }">
                    <button @click="servicesOpen = !servicesOpen" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 font-semibold rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition">
                        Services
                        <svg class="w-4 h-4 transition-transform" :class="servicesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="servicesOpen" x-cloak x-collapse class="ml-4 mt-1 space-y-1">
                        <a href="/smm" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 font-medium rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> SMM Panel
                        </a>
                        <a href="#services" @click="mobileMenu=false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 font-medium rounded-lg hover:bg-violet-50 hover:text-violet-600 transition">
                            <span class="w-2 h-2 rounded-full bg-violet-500"></span> Web Development
                        </a>
                        <a href="#services" @click="mobileMenu=false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 font-medium rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Mobile Apps
                        </a>
                        <a href="#services" @click="mobileMenu=false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 font-medium rounded-lg hover:bg-amber-50 hover:text-amber-600 transition">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Automation
                        </a>
                        <a href="#services" @click="mobileMenu=false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 font-medium rounded-lg hover:bg-rose-50 hover:text-rose-600 transition">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Custom Software
                        </a>
                        <a href="#services" @click="mobileMenu=false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 font-medium rounded-lg hover:bg-teal-50 hover:text-teal-600 transition">
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span> Digital Marketing
                        </a>
                    </div>
                </div>
                <a href="#work" @click="mobileMenu=false" class="block px-4 py-3 text-gray-700 font-semibold rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition">Our Work</a>
                <a href="#about" @click="mobileMenu=false" class="block px-4 py-3 text-gray-700 font-semibold rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition">About</a>
                <a href="#contact" @click="mobileMenu=false" class="block px-4 py-3 text-gray-700 font-semibold rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition">Contact</a>
                <div class="pt-4 border-t border-gray-100">
                    <a href="#contact" @click="mobileMenu=false" class="block w-full text-center px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition">Get a Quote</a>
                </div>
            </div>
        </div>
    </nav>


    {{-- ============================================= --}}
    {{-- HERO SECTION --}}
    {{-- ============================================= --}}
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 hero-mesh"></div>
        <div class="absolute inset-0 grid-pattern"></div>

        {{-- Floating decorations --}}
        <div class="absolute top-32 left-10 w-72 h-72 bg-emerald-400/10 rounded-full blur-3xl float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-400/10 rounded-full blur-3xl float-delay"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] opacity-[0.03]">
            <div class="w-full h-full border border-emerald-500 rounded-full spin-slow"></div>
            <div class="absolute inset-8 border border-emerald-500/50 rounded-full spin-slow" style="animation-direction: reverse;"></div>
            <div class="absolute inset-16 border border-emerald-500/30 rounded-full spin-slow"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Left: Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-8">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        IT Solutions from Nepal
                    </div>

                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tight text-gray-900 leading-[1.1] mb-8">
                        We Build
                        <span class="relative inline-block">
                            <span class="relative z-10 bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 via-cyan-600 to-emerald-600">Digital</span>
                            <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none"><path d="M2 8C30 3 70 2 100 5C130 8 170 9 198 4" stroke="url(#underline-grad)" stroke-width="4" stroke-linecap="round"/><defs><linearGradient id="underline-grad" x1="0" y1="0" x2="200" y2="0"><stop offset="0%" stop-color="#10b981"/><stop offset="100%" stop-color="#06b6d4"/></linearGradient></defs></svg>
                        </span>
                        <br>Products That
                        <span class="text-gray-900">Scale.</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-gray-500 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        From <strong class="text-gray-700">SMM panels</strong> to <strong class="text-gray-700">custom websites</strong>, <strong class="text-gray-700">mobile apps</strong>, and <strong class="text-gray-700">workflow automation</strong> — we engineer solutions that grow your business.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-12">
                        <a href="#contact" class="group px-8 py-4 bg-gradient-to-r from-emerald-600 to-cyan-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-emerald-200/40 hover:shadow-emerald-300/50 transition-all duration-300 hover:-translate-y-1 flex items-center justify-center gap-2">
                            Start Your Project
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#services" class="px-8 py-4 bg-white text-gray-700 border-2 border-gray-200 rounded-2xl font-bold text-lg hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50/50 transition-all duration-300 text-center">
                            Explore Services
                        </a>
                    </div>

                    {{-- Trust indicators --}}
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6 text-sm text-gray-400">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>100+ Projects</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>5+ Years</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>24/7 Support</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Visual --}}
                <div class="relative hidden lg:block">
                    {{-- Main card --}}
                    <div class="relative z-10">
                        {{-- Terminal/Code card --}}
                        <div class="bg-gray-900 rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50 glow-emerald">
                            {{-- Terminal header --}}
                            <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-700/50 bg-gray-800/50">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                <span class="ml-4 text-gray-400 text-xs font-mono">nepalboost ~ /projects</span>
                            </div>
                            {{-- Terminal body --}}
                            <div class="p-6 font-mono text-sm space-y-3">
                                <div>
                                    <span class="text-emerald-400">$</span>
                                    <span class="text-gray-300"> nepalboost</span>
                                    <span class="text-cyan-400"> init</span>
                                    <span class="text-gray-500"> --project</span>
                                    <span class="text-amber-400"> "your-idea"</span>
                                </div>
                                <div class="text-gray-400">
                                    <span class="text-emerald-400">✓</span> Analyzing requirements...
                                </div>
                                <div class="text-gray-400">
                                    <span class="text-emerald-400">✓</span> Designing architecture...
                                </div>
                                <div class="text-gray-400">
                                    <span class="text-emerald-400">✓</span> Building with Laravel + React...
                                </div>
                                <div class="text-gray-400">
                                    <span class="text-emerald-400">✓</span> Deploying to cloud...
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-700/50">
                                    <span class="text-emerald-400 font-bold">🚀 Project launched!</span>
                                    <span class="text-gray-500"> Your digital product is live.</span>
                                </div>
                                <div>
                                    <span class="text-emerald-400">$</span>
                                    <span class="text-gray-500 animate-pulse">█</span>
                                </div>
                            </div>
                        </div>

                        {{-- Floating mini cards --}}
                        <div class="absolute -top-6 -right-6 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 float z-20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">Mobile Apps</div>
                                    <div class="text-[10px] text-gray-400">iOS & Android</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-4 -left-6 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 float-delay z-20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">SMM Panel</div>
                                    <div class="text-[10px] text-gray-400">Instant Delivery</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute top-1/2 -right-10 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 float z-20" style="animation-delay: 2s;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">Automation</div>
                                    <div class="text-[10px] text-gray-400">Workflows</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- STATS BAR --}}
    {{-- ============================================= --}}
    <section class="relative py-16 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border-y border-gray-700/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="fade-up">
                    <div class="text-4xl lg:text-5xl font-black text-white counter mb-2">100<span class="text-emerald-400">+</span></div>
                    <div class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Projects Delivered</div>
                </div>
                <div class="fade-up" style="transition-delay: 0.1s">
                    <div class="text-4xl lg:text-5xl font-black text-white counter mb-2">50<span class="text-emerald-400">+</span></div>
                    <div class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Happy Clients</div>
                </div>
                <div class="fade-up" style="transition-delay: 0.2s">
                    <div class="text-4xl lg:text-5xl font-black text-white counter mb-2">1M<span class="text-emerald-400">+</span></div>
                    <div class="text-gray-400 text-sm font-semibold uppercase tracking-wider">SMM Orders</div>
                </div>
                <div class="fade-up" style="transition-delay: 0.3s">
                    <div class="text-4xl lg:text-5xl font-black text-white counter mb-2">24/7</div>
                    <div class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Support</div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- SERVICES SECTION --}}
    {{-- ============================================= --}}
    <section id="services" class="py-24 lg:py-32 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-6">What We Do</div>
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-6">
                    Everything You Need to <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-cyan-600">Go Digital</span>
                </h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">End-to-end IT solutions for businesses and creators. We don't just build — we engineer growth.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Card 1: SMM Panel --}}
                <div class="service-card bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:border-emerald-200 fade-up group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center mb-6 shadow-lg shadow-emerald-200/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">SMM Panel</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Boost social media presence with instant delivery. Instagram followers, TikTok views, YouTube watchtime & more at the cheapest rates.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full">Instagram</span>
                        <span class="px-3 py-1 bg-cyan-50 text-cyan-700 text-xs font-bold rounded-full">TikTok</span>
                        <span class="px-3 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-full">YouTube</span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">Facebook</span>
                    </div>
                    <a href="/smm" class="inline-flex items-center gap-2 text-emerald-600 font-bold text-sm group-hover:gap-3 transition-all">
                        Visit Panel <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Card 2: Web Development --}}
                <div class="service-card bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:border-violet-200 fade-up group" style="transition-delay: 0.1s">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center mb-6 shadow-lg shadow-violet-200/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Website Development</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Custom websites built with modern frameworks. From landing pages to complex SaaS platforms — responsive, fast, and SEO-optimized.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-violet-50 text-violet-700 text-xs font-bold rounded-full">Laravel</span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">React</span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full">Vue.js</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">Next.js</span>
                    </div>
                    <a href="#contact" class="inline-flex items-center gap-2 text-violet-600 font-bold text-sm group-hover:gap-3 transition-all">
                        Get Quote <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Card 3: Mobile App --}}
                <div class="service-card bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:border-blue-200 fade-up group" style="transition-delay: 0.2s">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Mobile App Development</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Native & cross-platform mobile applications for iOS and Android. From concept to App Store deployment.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">Flutter</span>
                        <span class="px-3 py-1 bg-cyan-50 text-cyan-700 text-xs font-bold rounded-full">React Native</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">Swift</span>
                    </div>
                    <a href="#contact" class="inline-flex items-center gap-2 text-blue-600 font-bold text-sm group-hover:gap-3 transition-all">
                        Get Quote <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Card 4: Workflow Automation --}}
                <div class="service-card bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:border-amber-200 fade-up group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mb-6 shadow-lg shadow-amber-200/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Workflow Automation</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Automate repetitive tasks and business processes. CRM integrations, API pipelines, and custom bots to save time and money.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full">APIs</span>
                        <span class="px-3 py-1 bg-orange-50 text-orange-700 text-xs font-bold rounded-full">Bots</span>
                        <span class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-full">CRM</span>
                    </div>
                    <a href="#contact" class="inline-flex items-center gap-2 text-amber-600 font-bold text-sm group-hover:gap-3 transition-all">
                        Get Quote <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Card 5: Software Development --}}
                <div class="service-card bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:border-rose-200 fade-up group" style="transition-delay: 0.1s">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center mb-6 shadow-lg shadow-rose-200/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Custom Software</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Tailored software solutions for your specific business needs. Inventory systems, billing platforms, dashboards & more.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full">SaaS</span>
                        <span class="px-3 py-1 bg-pink-50 text-pink-700 text-xs font-bold rounded-full">ERP</span>
                        <span class="px-3 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-full">Dashboards</span>
                    </div>
                    <a href="#contact" class="inline-flex items-center gap-2 text-rose-600 font-bold text-sm group-hover:gap-3 transition-all">
                        Get Quote <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Card 6: SEO & Digital Marketing --}}
                <div class="service-card bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:border-teal-200 fade-up group" style="transition-delay: 0.2s">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 to-green-500 flex items-center justify-center mb-6 shadow-lg shadow-teal-200/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">SEO & Marketing</h3>
                    <p class="text-gray-500 mb-6 leading-relaxed">Rank higher, get found, convert more. Technical SEO audit, content strategy, Google Ads, and social media campaigns.</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-full">SEO</span>
                        <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full">Google Ads</span>
                        <span class="px-3 py-1 bg-lime-50 text-lime-700 text-xs font-bold rounded-full">Analytics</span>
                    </div>
                    <a href="#contact" class="inline-flex items-center gap-2 text-teal-600 font-bold text-sm group-hover:gap-3 transition-all">
                        Get Quote <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- SMM PANEL HIGHLIGHT --}}
    {{-- ============================================= --}}
    <section id="smm" class="py-24 lg:py-32 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 via-transparent to-cyan-50/50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Left: Info --}}
                <div class="fade-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-6">Our Flagship Product</div>
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-6">
                        #1 SMM Panel in <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-cyan-600">Nepal</span>
                    </h2>
                    <p class="text-lg text-gray-500 mb-8 leading-relaxed">
                        Nepalboost SMM Panel provides the cheapest social media marketing services in Nepal & India. Boost your Instagram, TikTok, YouTube, and Facebook with instant delivery.
                    </p>

                    <div class="space-y-4 mb-10">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Cheapest Rates from NPR 1</h4>
                                <p class="text-gray-500 text-sm">Direct provider connections = lowest prices in the market.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center text-cyan-600 shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Instant Automated Delivery</h4>
                                <p class="text-gray-500 text-sm">99% of services start within seconds. Fully automated 24/7.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Local Payments: eSewa, Khalti</h4>
                                <p class="text-gray-500 text-sm">No credit card needed. Pay with eSewa, Khalti, IME Pay, or UPI.</p>
                            </div>
                        </div>
                    </div>

                    <a href="/smm" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-600 to-cyan-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-emerald-200/40 hover:shadow-emerald-300/50 transition-all hover:-translate-y-1">
                        Go to SMM Panel
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Right: Platform preview --}}
                <div class="fade-up relative" style="transition-delay: 0.2s">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-3xl transform rotate-2 opacity-20"></div>
                        <div class="relative bg-white rounded-3xl border border-gray-200 shadow-2xl overflow-hidden">
                            {{-- Mini browser chrome --}}
                            <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                                </div>
                                <div class="flex-1 ml-4 bg-white rounded-lg px-3 py-1.5 text-xs text-gray-400 border border-gray-200 font-mono">nepalboost.com/smm</div>
                            </div>
                            {{-- Mock dashboard --}}
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-gray-900 text-sm">Dashboard</h4>
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">NPR 100,000</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="bg-emerald-50 rounded-xl p-3 text-center">
                                        <div class="text-lg font-bold text-emerald-700">256</div>
                                        <div class="text-[10px] text-gray-500 font-semibold">Orders</div>
                                    </div>
                                    <div class="bg-cyan-50 rounded-xl p-3 text-center">
                                        <div class="text-lg font-bold text-cyan-700">89</div>
                                        <div class="text-[10px] text-gray-500 font-semibold">Active</div>
                                    </div>
                                    <div class="bg-purple-50 rounded-xl p-3 text-center">
                                        <div class="text-lg font-bold text-purple-700">1.2k</div>
                                        <div class="text-[10px] text-gray-500 font-semibold">Services</div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="text-xs font-bold text-gray-500 mb-3 uppercase">Recent Orders</div>
                                    <div class="space-y-2">
                                        @for($i = 0; $i < 3; $i++)
                                        <div class="flex items-center justify-between py-2 {{ $i < 2 ? 'border-b border-gray-100' : '' }}">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg {{ ['bg-pink-100','bg-red-100','bg-blue-100'][$i] }} flex items-center justify-center">
                                                    {!! ['<svg class="w-3.5 h-3.5 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.06 1.265.072 1.645.072 4.849 0 3.205-.012 3.584-.072 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.06-1.644.072-4.85.072-3.205 0-3.584-.012-4.85-.072-3.225-.148-4.771-1.664-4.919-4.919-.06-1.265-.072-1.644-.072-4.849 0-3.204.012-3.584.072-4.849.149-3.225 1.664-4.771 4.919-4.919 1.266-.06 1.645-.072 4.85-.072z"/></svg>', '<svg class="w-3.5 h-3.5 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z"/></svg>', '<svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'][$i] !!}
                                                </div>
                                                <div class="text-xs text-gray-700 font-medium">{{ ['IG Followers 10K','YT Views 50K','FB Likes 5K'][$i] }}</div>
                                            </div>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ ['bg-emerald-100 text-emerald-700','bg-amber-100 text-amber-700','bg-emerald-100 text-emerald-700'][$i] }}">{{ ['Completed','Processing','Completed'][$i] }}</span>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- HOW WE WORK / PROCESS --}}
    {{-- ============================================= --}}
    <section id="work" class="py-24 lg:py-32 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-6">Our Process</div>
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-6">
                    How We Build <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-cyan-600">Your Product</span>
                </h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">From idea to launch in 4 simple steps. We handle the complexity so you can focus on growth.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                {{-- Connecting line (desktop only) --}}
                <div class="hidden lg:block absolute top-20 left-[12.5%] right-[12.5%] h-0.5 bg-gradient-to-r from-emerald-200 via-cyan-200 to-purple-200"></div>

                {{-- Step 01: Discovery --}}
                <div class="text-center fade-up relative">
                    <div class="relative z-10 w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-200/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div class="text-xs font-black text-emerald-600 mb-2 uppercase tracking-widest">Step 01</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Discovery</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">We analyze your requirements, target audience, and business goals to create the perfect blueprint.</p>
                </div>

                {{-- Step 02: Design --}}
                <div class="text-center fade-up relative" style="transition-delay: 0.1s">
                    <div class="relative z-10 w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-cyan-200/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    </div>
                    <div class="text-xs font-black text-cyan-600 mb-2 uppercase tracking-widest">Step 02</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Design</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Beautiful, intuitive UI/UX design that users love. Figma prototypes for your review.</p>
                </div>

                {{-- Step 03: Develop --}}
                <div class="text-center fade-up relative" style="transition-delay: 0.2s">
                    <div class="relative z-10 w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-violet-200/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <div class="text-xs font-black text-violet-600 mb-2 uppercase tracking-widest">Step 03</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Develop</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Clean, scalable code using modern frameworks. Regular progress updates and demos.</p>
                </div>

                {{-- Step 04: Launch --}}
                <div class="text-center fade-up relative" style="transition-delay: 0.3s">
                    <div class="relative z-10 w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-200/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="text-xs font-black text-purple-600 mb-2 uppercase tracking-widest">Step 04</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Launch</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Deployed, tested, and ready for the world. We provide ongoing support and maintenance.</p>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- TECH STACK --}}
    {{-- ============================================= --}}
    <section class="py-20 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-up">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Technologies We Use</h3>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-16 opacity-60 hover:opacity-100 transition-opacity duration-500">
                @php
                $techs = [
                    ['name' => 'Laravel', 'svg' => '<svg viewBox="0 0 50 52" class="h-8 text-red-500"><path fill="currentColor" d="M49.626 11.564a.809.809 0 01.028.209v10.972a.8.8 0 01-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 01-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.801.801 0 010 39.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.02-.02.047-.034.069-.052.027-.021.05-.046.08-.064h.001L9.749.381a.802.802 0 01.803 0l9.464 5.452c.03.019.054.043.08.065.023.018.05.032.07.052.026.028.047.061.07.093.018.024.04.045.056.071.022.04.035.082.05.124.01.023.022.044.029.067a.82.82 0 01.027.21v20.53l8.007-4.609V10.97c0-.072.01-.142.028-.21.007-.024.02-.045.028-.068.016-.042.03-.085.051-.124.016-.026.038-.047.056-.071.023-.032.044-.065.07-.093.02-.02.048-.034.07-.052.027-.021.05-.046.08-.064h.001l9.464-5.453a.803.803 0 01.803 0l9.464 5.453c.03.019.054.043.08.065.022.018.049.032.069.052.027.028.048.061.071.093.018.024.04.045.055.071.023.04.036.082.051.124.009.023.022.044.028.068z"/></svg>'],
                    ['name' => 'React', 'svg' => '<svg viewBox="0 0 24 24" class="h-8 text-cyan-500"><path fill="currentColor" d="M14.23 12.004a2.236 2.236 0 01-2.235 2.236 2.236 2.236 0 01-2.236-2.236 2.236 2.236 0 012.235-2.236 2.236 2.236 0 012.236 2.236zm2.648-10.69c-1.346 0-3.107.96-4.888 2.622-1.78-1.653-3.542-2.602-4.887-2.602-.31 0-.592.068-.846.203-1.408.765-1.846 3.458-.828 7.175C2.016 10.46 0 12.153 0 13.615c0 .467.18.912.516 1.307.895 1.054 2.986 1.528 5.748 1.317 1.08 3.023 2.51 5.122 3.96 5.736.257.108.54.162.846.162 1.346 0 3.107-.96 4.888-2.622 1.78 1.653 3.542 2.602 4.887 2.602.31 0 .592-.068.846-.203 1.408-.765 1.846-3.458.828-7.175C21.984 13.54 24 11.847 24 10.385c0-.467-.18-.912-.516-1.307-.895-1.054-2.986-1.528-5.748-1.317-1.079-3.023-2.51-5.122-3.96-5.736a2.003 2.003 0 00-.846-.162z"/></svg>'],
                    ['name' => 'Vue.js', 'svg' => '<svg viewBox="0 0 24 24" class="h-8 text-emerald-500"><path fill="currentColor" d="M24 1.61h-9.94L12 5.16 9.94 1.61H0l12 20.78L24 1.61zM12 14.08L5.16 2.23h4.43L12 6.41l2.41-4.18h4.43L12 14.08z"/></svg>'],
                    ['name' => 'Flutter', 'svg' => '<svg viewBox="0 0 24 24" class="h-8 text-blue-500"><path fill="currentColor" d="M14.314 0L2.3 12 6.13 15.83 22.113 0H14.314zM14.314 11.908L8.474 17.749 14.314 23.591H22.113L16.272 17.749 22.113 11.908z"/></svg>'],
                    ['name' => 'Docker', 'svg' => '<svg viewBox="0 0 24 24" class="h-8 text-blue-600"><path fill="currentColor" d="M13.983 11.078h2.119a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.119a.186.186 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.954-5.43h2.118a.186.186 0 00.186-.186V3.574a.186.186 0 00-.186-.185h-2.118a.186.186 0 00-.185.185v1.888c0 .102.082.185.185.186m0 2.716h2.118a.187.187 0 00.186-.186V6.29a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.887c0 .102.082.185.185.186m-2.93 0h2.12a.186.186 0 00.184-.186V6.29a.185.185 0 00-.185-.185H8.1a.185.185 0 00-.185.185v1.887c0 .102.083.186.185.186m-2.964 0h2.119a.186.186 0 00.185-.186V6.29a.186.186 0 00-.185-.185H5.136a.186.186 0 00-.186.185v1.887c0 .102.084.186.186.186m5.893 2.715h2.118a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185m-2.93 0h2.12a.185.185 0 00.184-.185V9.006a.185.185 0 00-.184-.186h-2.12a.185.185 0 00-.184.185v1.888c0 .102.083.185.185.185m-2.964 0h2.119a.185.185 0 00.185-.185V9.006a.186.186 0 00-.185-.186H5.136a.186.186 0 00-.186.186v1.887c0 .102.084.185.186.185m-2.92 0h2.12a.185.185 0 00.184-.185V9.006a.185.185 0 00-.184-.186h-2.12a.185.185 0 00-.184.185v1.888c0 .102.082.185.185.185M23.763 9.89c-.065-.051-.672-.51-1.954-.51-.338.001-.676.03-1.01.087-.248-1.7-1.653-2.53-1.716-2.566l-.344-.199-.226.327c-.284.438-.49.922-.612 1.43-.23.97-.09 1.882.403 2.661-.595.332-1.55.413-1.744.42H.751a.751.751 0 00-.75.748 11.376 11.376 0 00.692 4.062c.545 1.428 1.355 2.48 2.41 3.124 1.18.723 3.1 1.137 5.275 1.137.983.003 1.963-.086 2.93-.266a12.248 12.248 0 003.823-1.389c.98-.567 1.86-1.288 2.61-2.136 1.252-1.418 1.998-2.997 2.553-4.4h.221c1.372 0 2.215-.549 2.68-1.009.309-.293.55-.65.707-1.046l.098-.288z"/></svg>'],
                    ['name' => 'Python', 'svg' => '<svg viewBox="0 0 24 24" class="h-8 text-yellow-500"><path fill="currentColor" d="M14.25.18l.9.2.73.26.59.3.45.32.34.34.25.34.16.33.1.3.04.26.02.2-.01.13V8.5l-.05.63-.13.55-.21.46-.26.38-.3.31-.33.25-.35.19-.35.14-.33.1-.3.07-.26.04-.21.02H8.77l-.69.05-.59.14-.5.22-.41.27-.33.32-.27.35-.2.36-.15.37-.1.35-.07.32-.04.27-.02.21v3.06H3.17l-.21-.03-.28-.07-.32-.12-.35-.18-.36-.26-.36-.36-.35-.46-.32-.59-.28-.73-.21-.88-.14-1.05-.05-1.23.06-1.22.16-1.04.24-.87.32-.71.36-.57.4-.44.42-.33.42-.24.4-.16.36-.1.32-.05.24-.01h.16l.06.01h8.16v-.83H6.18l-.01-2.75-.02-.37.05-.34.11-.31.17-.28.25-.26.31-.23.38-.2.44-.18.51-.15.58-.12.64-.1.71-.06.77-.04.84-.02 1.27.05zm-6.3 1.98l-.23.33-.08.41.08.41.23.34.33.22.41.09.41-.09.33-.22.23-.34.08-.41-.08-.41-.23-.33-.33-.22-.41-.09-.41.09z"/></svg>'],
                ];
                @endphp
                @foreach($techs as $tech)
                <div class="flex flex-col items-center gap-2 fade-up">
                    {!! $tech['svg'] !!}
                    <span class="text-xs font-bold text-gray-400">{{ $tech['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- ABOUT US --}}
    {{-- ============================================= --}}
    <section id="about" class="py-24 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Left visual --}}
                <div class="fade-up">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-100 to-cyan-100 rounded-3xl transform -rotate-3"></div>
                        <div class="relative bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-10 text-white shadow-2xl">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-lg">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 4V20L8 12L12 20V4" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 4V20H18C20.2091 20 22 18.2091 22 16V16C22 13.7909 20.2091 12 18 12H14M14 12H18C20.2091 12 22 10.2091 22 8V8C22 5.79086 20.2091 4 18 4H14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-black text-xl">Nepalboost</div>
                                    <div class="text-emerald-400 text-xs font-semibold uppercase tracking-widest">IT Solutions</div>
                                </div>
                            </div>
                            <blockquote class="text-lg leading-relaxed text-gray-300 italic mb-6">
                                "We believe technology should empower everyone. From a small shop in Kathmandu to a global brand — we build digital tools that scale with your ambition."
                            </blockquote>
                            <div class="flex items-center gap-4 pt-6 border-t border-gray-700">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-400 flex items-center justify-center text-white font-bold text-lg">P</div>
                                <div>
                                    <div class="font-bold">Prakash</div>
                                    <div class="text-sm text-gray-400">Founder & Lead Engineer</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: content --}}
                <div class="fade-up" style="transition-delay: 0.2s">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-6">About Us</div>
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-6">
                        Built in <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-cyan-600">Kathmandu</span>, For the World
                    </h2>
                    <p class="text-lg text-gray-500 mb-6 leading-relaxed">
                        Nepalboost is an IT solutions company founded by passionate engineers in Kathmandu, Nepal. We specialize in building digital products that solve real problems — from SMM automation to custom enterprise software.
                    </p>
                    <p class="text-lg text-gray-500 mb-8 leading-relaxed">
                        Our mission is simple: <strong class="text-gray-700">make powerful technology accessible and affordable</strong> for businesses in Nepal and beyond. We combine modern engineering practices with deep understanding of local markets.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="text-2xl font-black text-emerald-600 mb-1">5+</div>
                            <div class="text-sm text-gray-500 font-semibold">Years Experience</div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="text-2xl font-black text-emerald-600 mb-1">100%</div>
                            <div class="text-sm text-gray-500 font-semibold">Client Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- CONTACT / CTA SECTION --}}
    {{-- ============================================= --}}
    <section id="contact" class="py-24 lg:py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-900"></div>
        <div class="absolute inset-0 grid-pattern opacity-30"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-emerald-500/10 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Left: Content --}}
                <div class="text-white fade-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-6">Get In Touch</div>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight mb-6">
                        Let's Build Something <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-cyan-400">Amazing</span>
                    </h2>
                    <p class="text-lg text-gray-400 mb-10 leading-relaxed">
                        Have an idea? Need a website, app, or SMM solution? Let's talk. We respond within 24 hours.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-400">Email Us</div>
                                <a href="mailto:info@nepalboost.com" class="text-white font-bold hover:text-emerald-400 transition">info@nepalboost.com</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-400">Location</div>
                                <span class="text-white font-bold">Kathmandu, Nepal</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-400">Website</div>
                                <a href="https://nepalboost.com" class="text-white font-bold hover:text-emerald-400 transition">nepalboost.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Contact Form --}}
                <div class="fade-up" style="transition-delay: 0.2s">
                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/10 shadow-2xl">
                        <h3 class="text-xl font-bold text-white mb-6">Send us a message</h3>
                        <form class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2">Name</label>
                                    <input type="text" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" placeholder="Your name">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
                                    <input type="email" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" placeholder="you@example.com">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Service Needed</label>
                                <select class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                                    <option value="" class="text-gray-900">Select a service</option>
                                    <option value="smm" class="text-gray-900">SMM Panel</option>
                                    <option value="web" class="text-gray-900">Website Development</option>
                                    <option value="app" class="text-gray-900">Mobile App</option>
                                    <option value="automation" class="text-gray-900">Workflow Automation</option>
                                    <option value="software" class="text-gray-900">Custom Software</option>
                                    <option value="seo" class="text-gray-900">SEO & Marketing</option>
                                    <option value="other" class="text-gray-900">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Message</label>
                                <textarea rows="4" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none" placeholder="Tell us about your project..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white rounded-xl font-bold text-lg shadow-xl shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all hover:-translate-y-0.5 active:scale-[0.98]">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================= --}}
    {{-- FOOTER --}}
    {{-- ============================================= --}}
    <footer class="bg-gray-950 border-t border-gray-800/50 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <a href="/" class="flex items-center gap-3 mb-6 group">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M4 4V20L8 12L12 20V4" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 4V20H18C20.2091 20 22 18.2091 22 16V16C22 13.7909 20.2091 12 18 12H14M14 12H18C20.2091 12 22 10.2091 22 8V8C22 5.79086 20.2091 4 18 4H14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold text-white">Nepal<span class="text-emerald-400">boost</span></span>
                            <span class="block text-[10px] font-semibold text-gray-500 uppercase tracking-[0.2em] -mt-0.5">IT Solutions</span>
                        </div>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-md mb-6">
                        Your trusted IT partner from Kathmandu. We build websites, mobile apps, SMM tools, and custom software to help businesses grow digitally.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-xl bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-emerald-600 hover:text-white transition-all hover:scale-110">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-pink-600 hover:text-white transition-all hover:scale-110">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.06 1.265.072 1.645.072 4.849 0 3.205-.012 3.584-.072 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.06-1.644.072-4.85.072-3.205 0-3.584-.012-4.85-.072-3.225-.148-4.771-1.664-4.919-4.919-.06-1.265-.072-1.644-.072-4.849 0-3.204.012-3.584.072-4.849.149-3.225 1.664-4.771 4.919-4.919 1.266-.06 1.645-.072 4.85-.072zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-emerald-500 hover:text-white transition-all hover:scale-110">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-gray-700 hover:text-white transition-all hover:scale-110">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Services --}}
                <div>
                    <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-wider">Services</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="/smm" class="hover:text-emerald-400 transition">SMM Panel</a></li>
                        <li><a href="#services" class="hover:text-emerald-400 transition">Web Development</a></li>
                        <li><a href="#services" class="hover:text-emerald-400 transition">Mobile Apps</a></li>
                        <li><a href="#services" class="hover:text-emerald-400 transition">Workflow Automation</a></li>
                        <li><a href="#services" class="hover:text-emerald-400 transition">Custom Software</a></li>
                        <li><a href="#services" class="hover:text-emerald-400 transition">SEO & Marketing</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-wider">Company</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#about" class="hover:text-emerald-400 transition">About Us</a></li>
                        <li><a href="#contact" class="hover:text-emerald-400 transition">Contact</a></li>
                        <li><a href="{{ route('page.terms') }}" class="hover:text-emerald-400 transition">Terms of Service</a></li>
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-emerald-400 transition">Privacy Policy</a></li>
                        <li><a href="/blog" class="hover:text-emerald-400 transition">Blog</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800/50 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Nepalboost. All rights reserved.</p>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>Crafted with</span>
                    <svg class="w-4 h-4 text-emerald-500 fill-current pulse-glow" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span>in Kathmandu, Nepal</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scroll animation script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
        });
    </script>

</body>
</html>
