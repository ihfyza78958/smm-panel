<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TopupController extends Controller
{
    public function create()
    {
        return view('user.topup.create');
    }

    public function initiate()
    {
        $trx = Transaction::create([
            'user_id' => auth()->id(),
            'amount' => 0,
            'type' => 'deposit',
            'status' => 'pending',
            'transaction_id' => 'TRX-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'payment_method' => 'manual',
            'description' => 'Deposit Funds',
            'is_manual' => true,
        ]);

        return redirect()->route('user.topup.show', $trx->id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0', // It was failing validation here because we sent 0!
            'transaction_id' => 'nullable|string|max:50',
            'payment_method' => 'nullable|string',
        ]);

        // Always create and redirect to the invoice show page
        Log::info("Starting Topup Store", $request->all());
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

        // ==========================================
        // Trigger n8n Webhook for automated verification
        // ==========================================
        try {
            $webhookUrl = env('N8N_WEBHOOK_URL', 'http://localhost:5678/webhook/payment-verify');
            \Illuminate\Support\Facades\Http::timeout(3)->post($webhookUrl, [
                'transaction_internal_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'user_email' => $transaction->user->email ?? '',
                'user_name' => $transaction->user->name ?? '',
                'amount' => $transaction->amount,
                'transaction_code' => $transaction->transaction_id,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("n8n webhook failed: " . $e->getMessage());
        }

        return back()->with('success', 'Payment submitted! System is verifying your transaction. If delayed, contact WhatsApp: 9843652752.');
    }
}
