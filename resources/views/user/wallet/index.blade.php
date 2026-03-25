<x-app-layout>
    <x-slot name="header">Add Funds</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Payment Methods -->
        <div class="space-y-6">
            
            <!-- Scan & Pay (Recommended) -->
            <div x-data="{ open: false }" class="card hover:border-indigo-400 transition cursor-pointer relative overflow-hidden group">
                <div @click="open = !open" class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-xs border border-indigo-100">
                        <!-- Corrected QR Icon -->
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm12 0h6v6h-6V3zm0 12h6v6h-6v-6zM3 15h6v6H3v-6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Scan & Pay</h3>
                        <p class="text-xs text-gray-500">eSewa / Khalti / Mobile Banking</p>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <span class="badge bg-indigo-100 text-indigo-700">Recommended</span>
                         <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg>
                    </div>
                </div>

                <div x-show="open" class="mt-4 pt-4 border-t border-gray-100" x-transition style="display: none;">
                    <a href="{{ route('user.topup.initiate') }}" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700 transition flex justify-center items-center gap-2">
                            Proceed to Payment Details 
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                </div>
            </div>

            <!-- Esewa -->
            @if(config('gateways.esewa.enabled'))
                <div x-data="{ open: false }" class="card hover:border-green-400 transition cursor-pointer relative overflow-hidden">
                    <div @click="open = !open" class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center text-green-600 font-bold text-xs border border-green-100">
                            <!-- eSewa Logo Placeholder -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">eSewa</h3>
                            <p class="text-xs text-gray-500">Instant Deposit</p>
                        </div>
                        <div class="ml-auto">
                            <svg class="w-5 h-5 text-gray-400" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <div x-show="open" class="mt-4 pt-4 border-t border-gray-100" x-transition>
                        <form action="{{ route('payment.esewa.process') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="number" name="amount" placeholder="Amount (NPR)" min="10" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700 transition">Pay</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card relative opacity-70 grayscale-[0.5] hover:opacity-100 hover:grayscale-0 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center text-green-600 font-bold text-xs border border-green-100">
                            eSewa
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Pay with eSewa</h3>
                            <p class="text-xs text-gray-500">Coming Soon</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Khalti -->
            @if(config('gateways.khalti.enabled'))
                <div x-data="{ open: false }" class="card hover:border-purple-400 transition cursor-pointer relative overflow-hidden">
                    <div @click="open = !open" class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 font-bold text-xs border border-purple-100">
                            <!-- Khalti Logo Placeholder -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Khalti</h3>
                            <p class="text-xs text-gray-500">Instant Deposit</p>
                        </div>
                         <div class="ml-auto">
                            <svg class="w-5 h-5 text-gray-400" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                     <div x-show="open" class="mt-4 pt-4 border-t border-gray-100" x-transition>
                        <form action="{{ route('payment.khalti.process') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="number" name="amount" placeholder="Amount (NPR)" min="10" required class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm">
                            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-purple-700 transition">Pay</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card relative opacity-70 grayscale-[0.5] hover:opacity-100 hover:grayscale-0 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 font-bold text-xs border border-purple-100">
                            Khalti
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Pay with Khalti</h3>
                            <p class="text-xs text-gray-500">Coming Soon</p>
                        </div>
                    </div>
                </div>
            @endif

             <!-- Bank Transfer (Coming Soon) -->
             <div class="card bg-gray-50 border-gray-200 opacity-60 cursor-not-allowed">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 font-bold text-xs border border-gray-200">
                        BANK
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700">Bank Transfer</h3>
                        <p class="text-xs font-semibold text-blue-600 mt-1">Coming Soon</p>
                    </div>
                </div>
                <div class="text-sm text-gray-400 bg-white p-3 rounded-lg border border-gray-100 mb-4 blur-[2px]">
                    <p><strong>Bank:</strong> Nabil Bank Ltd</p>
                    <p><strong>Acct Name:</strong> SMM Services Pvt Ltd</p>
                    <p><strong>Acct No:</strong> 012301230123</p>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="card h-full">
            <h3 class="font-bold text-lg text-gray-900 mb-6">Recent Transactions</h3>
            <div class="flow-root">
                <ul class="-my-4 divide-y divide-gray-100">
                    @forelse(auth()->user()->transactions()->latest()->take(5)->get() as $txn)
                    <li class="py-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-full {{ $txn->type == 'deposit' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $txn->type == 'deposit' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($txn->type) }}</p>
                                <p class="text-xs text-gray-500">{{ $txn->created_at->format('M d, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900 {{ $txn->type == 'deposit' ? 'text-green-600' : '' }}">
                                {{ $txn->type == 'deposit' ? '+' : '-' }} NPR {{ number_format($txn->amount, 2) }}
                            </p>
                            <span class="badge {{ $txn->status == 'completed' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-50 text-yellow-600' }}">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="py-8 text-center text-gray-400 text-sm">No transactions yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</x-app-layout>
