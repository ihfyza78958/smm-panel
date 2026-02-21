<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Financial Transactions</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.transactions.export-csv') }}" class="bg-white border border-gray-200 px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="text-sm text-gray-500 mb-1">Total Deposits</div>
            <div class="text-2xl font-bold text-gray-900">NPR {{ number_format($totalDeposits, 2) }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="text-sm text-gray-500 mb-1">This Month</div>
            <div class="text-2xl font-bold text-indigo-600">NPR {{ number_format($monthDeposits, 2) }}</div>
        </div>
         <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="text-sm text-gray-500 mb-1">Pending</div>
            <div class="text-2xl font-bold text-orange-500">NPR {{ number_format($pendingDeposits, 2) }}</div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
             <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Ref ID (User)</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Gateway</th>
                        <th class="px-6 py-4 font-semibold">Type</th>
                        <th class="px-6 py-4 font-semibold text-right">Amount</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Date</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">
                            #{{ $tx->id }}
                        </td>
                         <td class="px-6 py-4 text-xs">
                            <div class="font-mono text-indigo-600 font-bold">{{ $tx->transaction_id ?? '-' }}</div>
                            <div class="text-gray-400 text-[10px] mt-0.5 truncate max-w-[150px]" title="{{ $tx->description }}">{{ $tx->description }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $tx->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $tx->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                @if($tx->payment_method == 'esewa')
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> eSewa
                                @elseif($tx->payment_method == 'khalti')
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Khalti
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> {{ ucfirst($tx->payment_method) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                             @if($tx->type == 'deposit')
                                <span class="text-green-600 font-medium text-xs">Deposit</span>
                            @else
                                <span class="text-red-600 font-medium text-xs">Spend</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            NPR {{ number_format($tx->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $tx->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-gray-500 text-xs">
                             {{ $tx->created_at->format('M d, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($tx->status !== 'completed' && $tx->status !== 'failed')
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.transactions.approve', $tx) }}" method="POST">
                                        @csrf
                                        <button title="Approve" onclick="return confirm('Approve this transaction? Funds will be added.')" class="p-1.5 bg-green-50 text-green-600 rounded hover:bg-green-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.transactions.reject', $tx) }}" method="POST">
                                        @csrf
                                        <button title="Reject" onclick="return confirm('Reject this transaction?')" class="p-1.5 bg-red-50 text-red-600 rounded hover:bg-red-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="9" class="px-6 py-10 text-center text-gray-500">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
    </div>
</x-admin-layout>
