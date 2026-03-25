<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopupController extends Controller
{
    public function create()
    {
        return view('user.topup.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:1',
            'transaction_id' => 'nullable|string|max:50',
            'payment_method' => 'nullable|string',
        ]);

        // Always create and redirect to the invoice show page
        $trx = Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount ?? 0,
            'type' => 'deposit',
            'status' => $request->has('transaction_id') ? 'review' : 'pending',
            'transaction_id' => $request->has('transaction_id') ? $request->transaction_id : 'TRX-' . strtoupper(Str::random(8)),
            'payment_method' => $request->has('payment_method') ? $request->payment_method : 'manual',
            'description' => 'Deposit Funds',
            'is_manual' => true,
        ]);

        return redirect()->route('user.topup.show', $trx->id)->with('success', 'Payment registered! Please review your invoice details.');
    }

    public function show(Transaction $transaction)
    {
        // Ensure user owns this transaction
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.topup.invoice', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $rules = [
            'transaction_id' => 'required|string|max:50',
            'payment_method' => 'nullable|string|in:esewa,khalti,bank,manual',
        ];
        
        if ($transaction->amount <= 0) {
            $rules['amount'] = 'required|numeric|min:10';
        }
        
        $request->validate($rules);

        $updateData = [
            'transaction_id' => $request->transaction_id, // User provided ref
            'payment_method' => $request->payment_method ?? 'manual',
            'status' => 'review', // Mark for admin review
            'description' => 'User submitted payment ref: ' . $request->transaction_id,
        ];
        
        if ($request->has('amount')) {
            $updateData['amount'] = $request->amount;
        }

        $transaction->update($updateData);

        return back()->with('success', 'Payment submitted! Admin will verify shortly. If delayed, contact WhatsApp: 9843652752.');
    }
}
