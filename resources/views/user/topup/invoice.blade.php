<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice #') . $transaction->id }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        method: 'esewa',
        get title() {
            if(this.method === 'esewa') return 'eSewa Mobile Wallet';
            if(this.method === 'khalti') return 'Khalti Digital Wallet';
            if(this.method === 'bank') return 'Bank Transfer';
            return 'Select Method';
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="md:grid md:grid-cols-3 md:gap-8">
                <!-- Left Column: Instructions -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Method Selector -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                        <select x-model="method" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="esewa">eSewa Mobile Wallet</option>
                            <option value="khalti">Khalti Digital Wallet</option>
                            <option value="bank">Bank Transfer (Nabil/Global IME/NIC)</option>
                        </select>
                    </div>

                    <!-- Dynamic Instructions -->
                    <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Payment Instructions</h3>
                        
                        <!-- eSewa Instructions -->
                        <div x-show="method === 'esewa'" class="space-y-6">
                            <div class="flex flex-col items-center justify-center p-6 bg-green-50 rounded-xl border-2 border-dashed border-green-200">
                                <img src="{{ asset('qrs/qr_code.jpg') }}" class="w-64 h-64 object-contain rounded-lg shadow-sm" alt="Payment QR">
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500 uppercase tracking-widest font-bold">Scan to Pay</p>
                                    <p class="text-xs text-gray-400 mt-1">Use eSewa / Khalti / Mobile Banking</p>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                                <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                    <span class="font-bold text-gray-700">Account Details</span>
                                    <img src="https://esewa.com.np/common/images/esewa_logo.png" class="h-6" alt="eSewa">
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="flex justify-between items-center border-b border-dashed border-gray-100 pb-2">
                                        <span class="text-gray-500 text-sm">Account Name</span>
                                        <span class="font-bold text-gray-900">PRAKASH CHAND KUSHWAHA</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b border-dashed border-gray-100 pb-2">
                                        <span class="text-gray-500 text-sm">eSewa ID</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-bold text-lg text-green-600">9843652752</span>
                                            <button onclick="navigator.clipboard.writeText('9843652752')" class="text-gray-400 hover:text-green-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center bg-yellow-50 p-2 rounded">
                                        <span class="text-gray-500 text-sm">Remarks (Required)</span>
                                        <span class="font-bold text-indigo-600">Inv #{{ $transaction->id }}</span>
                                    </div>
                                </div>
                            </div>
                             <div class="text-center text-xs text-gray-400">
                                After payment, please take a screenshot and verify via ticket/whatsapp if balance is not added within 10 minutes.
                            </div>
                        </div>

                        <!-- Khalti Instructions -->
                        <div x-show="method === 'khalti'" class="space-y-4" style="display: none;">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://d1yjjnpx0p53s8.cloudfront.net/styles/logo-original-577x577/s3/102017/untitled-1_15.png" class="h-10" alt="Khalti">
                                <span class="text-gray-500 text-sm">Digital Wallet</span>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-md border border-purple-100 text-purple-800 text-sm">
                                <strong>Instructions:</strong> Search for "SMM Nepal" in Khalti Bazaar or transfer to the ID below.
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                                <div class="text-gray-500">Khalti ID:</div>
                                <div class="font-mono font-bold text-lg">9800000000</div>
                                <div class="text-gray-500">Remarks:</div>
                                <div class="font-bold text-purple-600 bg-purple-50 inline-block px-2 rounded">Inv #{{ $transaction->id }}</div>
                            </div>
                        </div>

                        <!-- Bank Instructions -->
                        <div x-show="method === 'bank'" class="space-y-4" style="display: none;">
                             <div class="flex items-center gap-4 mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                <span class="text-gray-500 text-sm">Direct Bank Transfer</span>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-md border border-blue-100 text-blue-800 text-sm">
                                <strong>Important:</strong> Deposits via Bank Transfer may take up to 2-4 hours to verify. Always use <strong>Inv #{{ $transaction->id }}</strong> as the reference.
                            </div>
                            <div class="border rounded-md p-4 mt-4">
                                <h4 class="font-bold text-gray-800 mb-2">Nabil Bank</h4>
                                <div class="grid grid-cols-3 gap-2 text-sm">
                                    <div class="text-gray-500">Account Name:</div>
                                    <div class="col-span-2 font-bold">NEPALBOOST PVT LTD</div>
                                    <div class="text-gray-500">Account No:</div>
                                    <div class="col-span-2 font-mono font-bold">00100010000012345</div>
                                    <div class="text-gray-500">Branch:</div>
                                    <div class="col-span-2">Teendhara Branch, Kathmandu</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary -->
                <div class="md:col-span-1 mt-8 md:mt-0">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-t-2xl p-6 text-white text-center shadow-lg">
                        <div class="text-sm opacity-80 uppercase tracking-wide font-bold">Total Due</div>
                        <div class="text-4xl font-extrabold mt-2 tracking-tight">
                            @if($transaction->amount > 0)
                                Rs. {{ number_format($transaction->amount, 2) }}
                            @else
                                <span class="text-2xl text-indigo-200">Amount Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white p-6 shadow-xl border border-gray-100 border-t-0 rounded-b-2xl space-y-4">
                        <div class="flex justify-between text-sm py-3 border-b border-dashed border-gray-200">
                            <span class="text-gray-500 font-medium">Invoice ID</span>
                            <span class="font-bold text-gray-900">#{{ $transaction->id }}</span>
                        </div>
                         <div class="flex justify-between text-sm py-3 border-b border-dashed border-gray-200">
                            <span class="text-gray-500 font-medium">Transaction Ref</span>
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $transaction->transaction_id }}</span>
                        </div>
                        <div class="flex justify-between text-sm py-3 border-b border-dashed border-gray-200">
                            <span class="text-gray-500 font-medium">Status</span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide 
                                {{ $transaction->status == 'completed' ? 'bg-green-100 text-green-700' : 
                                   ($transaction->status == 'review' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ $transaction->status == 'review' ? 'Under Review' : $transaction->status }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm py-3">
                            <span class="text-gray-500 font-medium">Date</span>
                            <span class="font-medium">{{ $transaction->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <!-- Error Display -->
                            @if($errors->any())
                                <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm">
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="bg-green-50 text-green-600 p-3 rounded-lg mb-4 text-sm font-bold">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($transaction->status === 'pending')
                                <form action="{{ route('user.topup.update', $transaction->id) }}" method="POST" class="space-y-5">
                                    @csrf
                                    @method('PUT')
                                    <!-- Simplified Payment Method -->
                                    <input type="hidden" name="payment_method" value="manual">
                                    
                                    @if($transaction->amount <= 0)
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Amount Paid (NPR) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="amount" required min="10" 
                                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 pl-10 py-2.5" 
                                                placeholder="e.g. 500">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 font-bold">
                                                रु
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Enter Transaction Code <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" name="transaction_id" required 
                                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 pl-10 py-2.5" 
                                                placeholder="e.g. 9S123XYZ89">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1.5 ml-1">You can find this in your payment app confirmation.</p>
                                    </div>

                                    <button type="submit" 
                                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transform transition hover:-translate-y-0.5">
                                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                            <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                        Verify Payment
                                    </button>
                                </form>
                            @elseif($transaction->status === 'review')
                                <div class="bg-yellow-50 border border-yellow-100 rounded p-4 text-center">
                                    <p class="text-yellow-700 font-bold text-sm">Payment Under Review</p>
                                    <p class="text-xs text-yellow-600 mt-1">Admin will verify and add funds shortly.</p>
                                </div>
                            @elseif($transaction->status === 'completed')
                                <div class="bg-green-50 border border-green-100 rounded p-4 text-center">
                                    <p class="text-green-700 font-bold text-sm">Payment Successful</p>
                                    <p class="text-xs text-green-600 mt-1">Funds added to wallet.</p>
                                </div>
                            @endif
                            
                             <p class="text-xs text-center text-gray-400 mt-4">Use the "Transaction Ref" if looking for support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
