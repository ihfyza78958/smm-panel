<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nepalboost - Authentication</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 flex flex-col justify-center items-center min-h-screen">
    
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Brand Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-8 text-center text-white">
            <a href="/">
                <h1 class="text-3xl font-bold tracking-tight">Nepalboost</h1>
            </a>
            <p class="text-indigo-200 text-sm mt-2">The #1 Social Media Marketing Panel</p>
        </div>

        <div class="p-8">
            {{ $slot }}
        </div>
    </div>
    
    <!-- Footer Links -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <a href="{{ route('page.privacy') }}" class="hover:text-gray-900">Privacy</a>
        <span class="mx-2">•</span>
        <a href="{{ route('page.terms') }}" class="hover:text-gray-900">Terms</a>
        <span class="mx-2">•</span>
        <a href="https://nepalboost.com" class="hover:text-gray-900">Nepalboost IT</a>
    </div>

</body>
</html>
