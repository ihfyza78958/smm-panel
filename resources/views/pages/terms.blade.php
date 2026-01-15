<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service - Nepalboost</title>
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
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Terms of Service</h1>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 prose prose-indigo max-w-none">
                <h3>1. General</h3>
                <p>By placing an order with Nepalboost, you automatically accept all the below-listed terms of service weather you read them or not.</p>
                <p>We reserve the right to change these terms of service without notice. You are expected to read all terms of service before placing any order to insure you are up to date with any changes or future changes.</p>
                <p>You will only use the Nepalboost website in a manner which follows all agreements made with Instagram/Facebook/Twitter/Youtube/Other social media site on their individual Terms of Service page.</p>

                <h3>2. Service</h3>
                <p>Nepalboost will only be used to promote your Instagram/Twitter/Facebook or Social account and help boost your "Appearance" only.</p>
                <p>We DO NOT guarantee your new followers will interact with you, we simply guarantee you to get the followers you pay for.</p>
                <p>We DO NOT guarantee 100% of our accounts will have a profile picture, full bio and uploaded pictures, although we strive to make this the reality for all accounts.</p>
                
                <h3>3. Refund Policy</h3>
                <p>No refunds will be made to your payment method. After a deposit has been completed, there is no way to reverse it. You must use your balance on orders from Nepalboost.</p>
                <p>You agree that once you complete a payment, you will not file a dispute or a chargeback against us for any reason.</p>
                <p>If you file a dispute or chargeback against us after a deposit, we reserve the right to terminate all future orders, ban you from our site. We also reserve the right to take away any followers or likes we delivered to your or your clients Instagram/Facebook/Twitter or other social media account.</p>

                <h3>4. Privacy Policy</h3>
                <p>This policy covers how we use your personal information. We take your privacy seriously and will take all measures to protect your personal information.</p>
                <p>Any personal information received will only be used to fill your order. We will not sell or redistribute your information to anyone. All information is encrypted and saved in secure servers.</p>
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
