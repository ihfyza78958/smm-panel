<x-app-layout>
    <x-slot name="header">Add Funds</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="card">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Deposit Funds</h2>
                    <p class="text-sm text-gray-500">You'll receive an invoice with payment instructions.</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.topup.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Amount (NPR)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-medium text-sm">Rs.</span>
                        </div>
                        <input type="number" name="amount" value="{{ old('amount') }}" class="input-field pl-10" placeholder="500" min="10" required autofocus>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">Minimum deposit: NPR 10</p>
                </div>

                <div class="flex gap-2">
                    @foreach([100, 200, 500, 1000] as $preset)
                        <button type="button" onclick="document.querySelector('[name=amount]').value={{ $preset }}"
                            class="flex-1 py-1.5 text-xs font-semibold border border-gray-200 rounded-lg hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
                            {{ $preset }}
                        </button>
                    @endforeach
                </div>

                <button type="submit" class="w-full btn-primary py-3 text-base">
                    Create Invoice
                </button>
            </form>

            <div class="mt-5 pt-5 border-t border-gray-100">
                <p class="text-xs text-center text-gray-400">After submitting, follow the instructions on the invoice to complete your payment. Funds are added after admin verification.</p>
            </div>
        </div>

        <p class="text-center mt-4 text-sm text-gray-500">
            Prefer instant payment?
            <a href="{{ route('wallet.index') }}" class="text-indigo-600 font-semibold hover:underline">Use eSewa / Khalti</a>
        </p>
    </div>
</x-app-layout>
