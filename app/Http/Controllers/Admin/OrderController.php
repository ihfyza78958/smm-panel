<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('link', 'like', '%' . $search . '%')
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%'));
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $orders = $query->latest()->paginate(50);

        // Stats
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'canceled' => Order::where('status', 'canceled')->count(),
            'partial' => Order::where('status', 'partial')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'service', 'provider']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,in_progress,completed,partial,canceled,cancelled,refunded',
            'start_count' => 'nullable|integer|min:0',
            'remains' => 'nullable|integer|min:0',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        // Normalize cancelled → canceled
        if ($newStatus === 'cancelled') {
            $newStatus = 'canceled';
        }

        DB::transaction(function () use ($order, $request, $oldStatus, $newStatus) {
            // Handle refund if canceling or refunding
            if (in_array($newStatus, ['canceled', 'refunded']) && !in_array($oldStatus, ['canceled', 'refunded'])) {
                $refundAmount = $order->charge;

                // For partial, calculate refund based on remains
                if ($newStatus === 'refunded' && $request->filled('remains') && $request->remains > 0) {
                    $refundAmount = ($order->charge / $order->quantity) * $request->remains;
                }

                $order->user->increment('balance', $refundAmount);
                $order->user->decrement('spent', min($order->user->spent, $refundAmount));

                $order->user->transactions()->create([
                    'amount' => $refundAmount,
                    'type' => 'refund',
                    'description' => "Admin refund for Order #{$order->id} ({$oldStatus} → {$newStatus})",
                    'status' => 'completed',
                ]);
            }

            $updateData = ['status' => $newStatus];

            if ($request->filled('start_count')) {
                $updateData['start_count'] = $request->start_count;
            }
            if ($request->filled('remains')) {
                $updateData['remains'] = $request->remains;
            }
            if ($newStatus === 'completed') {
                $updateData['completed_at'] = now();
            }

            $order->update($updateData);
        });

        ActivityLog::log('order_status_changed', "Order #{$order->id} status: {$oldStatus} → {$newStatus}");

        return back()->with('success', "Order #{$order->id} updated to {$newStatus}.");
    }

    /**
     * Bulk cancel and refund selected orders.
     */
    public function bulkCancel(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $cancelled = 0;

        DB::transaction(function () use ($request, &$cancelled) {
            $orders = Order::whereIn('id', $request->order_ids)
                ->whereIn('status', ['pending', 'processing'])
                ->get();

            foreach ($orders as $order) {
                $order->user->increment('balance', $order->charge);
                $order->user->decrement('spent', min($order->user->spent, $order->charge));

                $order->user->transactions()->create([
                    'amount' => $order->charge,
                    'type' => 'refund',
                    'description' => "Bulk cancel refund for Order #{$order->id}",
                    'status' => 'completed',
                ]);

                $order->update(['status' => 'canceled']);
                $cancelled++;
            }
        });

        ActivityLog::log('bulk_order_cancel', "Bulk cancelled {$cancelled} orders");

        return back()->with('success', "{$cancelled} orders cancelled and refunded.");
    }
}
