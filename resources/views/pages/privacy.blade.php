<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy - Nepalboost</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="antialiased bg-gray-50 text-gray-800">
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-2">
                     <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg">S</div>
                    <a href="/" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700">Nepalboost</a>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Home</a>
                    <a href="{{ url('/#services') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Services</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Privacy Policy</h1>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 prose prose-indigo max-w-none">
                <p class="lead">Your privacy is important to us. It is Nepalboost's policy to respect your privacy regarding any information we may collect from you across our website.</p>
                
                <h3>1. Information We Collect</h3>
                <p>We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent.</p>
                <p>The types of check we perform may include:</p>
                <ul>
                    <li>Name and Contact information</li>
                    <li>Payment details (Processed securely via our payment partners)</li>
                    <li>Social Media Profile URLs (For order processing)</li>
                </ul>

                <h3>2. How We Use Information</h3>
                <p>We use the information we collect in various ways, including to:</p>
                <ul>
                    <li>Provide, operate, and maintain our website</li>
                    <li>Improve, personalize, and expand our website</li>
                    <li>Understand and analyze how you use our website</li>
                    <li>Develop new products, services, features, and functionality</li>
                    <li>Process your transactions</li>
                </ul>

                <h3>3. Log Files</h3>
                <p>Nepalboost follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services' analytics.</p>

                <h3>4. Cookies</h3>
                <p>Like any other website, Nepalboost uses 'cookies'. These cookies are used to store information including visitors' preferences, and the pages on the website that the visitor accessed or visited.</p>

                <h3>5. Security</h3>
                <p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>
            </div>
        </div>
    </div>
    
     <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Nepalboost. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
