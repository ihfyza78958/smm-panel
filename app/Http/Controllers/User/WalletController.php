<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Payment\EsewaGateway;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->transactions()->latest()->paginate(20);
        return view('user.wallet.index', compact('transactions'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'gateway' => 'required|in:esewa,khalti,imepay'
        ]);

        $amount = $request->amount;
        $gateway = $request->gateway;
        $transactionId = uniqid('txn_');

        if ($gateway === 'esewa') {
            $esewa = new EsewaGateway();
            $data = $esewa->initiate($amount, $transactionId);
            return view('user.wallet.redirect', ['data' => $data]);
        }

        return back()->with('error', 'Gateway not implemented yet.');
    }

    public function success(Request $request, $gateway)
    {
        if ($gateway === 'esewa') {
            $esewa = new EsewaGateway();
            $result = $esewa->verify($request);

            if ($result['status']) {
                $user = auth()->user();

                // Idempotency check: Prevent double-crediting
                $existingTx = Transaction::where('transaction_id', $result['transaction_id'])
                    ->where('status', 'completed')
                    ->exists();

                if ($existingTx) {
                    return redirect()->route('wallet.index')
                        ->with('success', 'Payment already processed.');
                }

                $user->increment('balance', $result['amount']);

                $user->transactions()->create([
                    'amount' => $result['amount'],
                    'type' => 'deposit',
                    'payment_method' => 'esewa',
                    'transaction_id' => $result['transaction_id'],
                    'status' => 'completed',
                    'description' => 'Deposit via eSewa'
                ]);

                return redirect()->route('wallet.index')
                    ->with('success', 'Deposit successful!');
            }
        }

        return redirect()->route('wallet.index')
            ->with('error', 'Payment verification failed.');
    }

    public function failure(Request $request, $gateway)
    {
        return redirect()->route('wallet.index')
            ->with('error', ucfirst($gateway) . ' payment was cancelled or failed.');
    }
}
