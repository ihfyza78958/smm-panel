<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = \App\Models\Transaction::with('user')->latest()->paginate(20);
        $totalDeposits = \App\Models\Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $monthDeposits = \App\Models\Transaction::where('type', 'deposit')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        $pendingDeposits = \App\Models\Transaction::where('type', 'deposit')
            ->where('status', 'pending')
            ->sum('amount');
            
        return view('admin.transactions.index', compact('transactions', 'totalDeposits', 'monthDeposits', 'pendingDeposits'));
    }

    public function exportCsv()
    {
        $filename = 'transactions-' . now()->format('Y-m-d') . '.csv';
        
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Transaction ID', 'User', 'Email', 'Gateway', 'Type', 'Amount', 'Status', 'Date']);
            
            \App\Models\Transaction::with('user')->orderBy('id', 'desc')->chunk(500, function ($transactions) use ($handle) {
                foreach ($transactions as $tx) {
                    fputcsv($handle, [
                        $tx->id,
                        $tx->transaction_id ?? '-',
                        $tx->user->name ?? 'Unknown',
                        $tx->user->email ?? '-',
                        $tx->payment_method ?? '-',
                        $tx->type,
                        $tx->amount,
                        $tx->status,
                        $tx->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });
            
            fclose($handle);
        });
        
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");
        
        return $response;
    }

    public function approve(\App\Models\Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            return back()->with('error', 'Transaction already completed');
        }

        \DB::transaction(function () use ($transaction) {
            $transaction->update(['status' => 'completed']);
            // Only add balance if it's a deposit
            if ($transaction->type === 'deposit') {
                $transaction->user->increment('balance', $transaction->amount);
            }
        });

        return back()->with('success', 'Transaction approved and funds added.');
    }

    public function reject(\App\Models\Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            return back()->with('error', 'Cannot reject a completed transaction');
        }

        $transaction->update(['status' => 'failed']);
        return back()->with('success', 'Transaction rejected.');
    }
}
