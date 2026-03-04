<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\ActivityLog;
use App\Services\Smm\JapLikeProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->whereHas('services', fn($q) => $q->where('is_active', true))
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        return view('user.orders.new', compact('categories'));
    }

    /**
     * Return services for a given category as JSON (used by the order form via fetch).
     */
    public function categoryServices(Category $category)
    {
        $services = $category->services()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'price', 'min_quantity', 'max_quantity', 'description'])
            ->map(fn($s) => [
                'id'    => $s->id,
                'name'  => $s->name,
                'price' => (float) $s->price,
                'min'   => $s->min_quantity,
                'max'   => $s->max_quantity,
                'desc'  => $s->description ?? '',
            ]);

        return response()->json($services);
    }

    public function history(Request $request)
    {
        $query = auth()->user()->orders()->with('service');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('link', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->latest()->paginate(20);
        return view('user.orders.history', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'link'       => 'required|string|max:500',
            'quantity'   => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($request->service_id);
        $user = auth()->user();

        if (!$service->is_active) {
            return back()->withErrors(['service_id' => 'This service is currently unavailable.']);
        }

        if ($request->quantity < $service->min_quantity || $request->quantity > $service->max_quantity) {
            return back()->withErrors(['quantity' => "Quantity must be between {$service->min_quantity} and {$service->max_quantity}"]);
        }

        // Apply user discount
        $price = $user->getDiscountedPrice($service->price);
        $totalPrice = ($price / 1000) * $request->quantity;

        if ($user->balance < $totalPrice) {
            return back()->withErrors(['balance' => 'Insufficient balance. Please deposit funds.']);
        }

        try {
            DB::beginTransaction();

            // Deduct Balance & Track Spending
            $user->decrement('balance', $totalPrice);
            $user->increment('spent', $totalPrice);

            // Create Transaction Log
            $user->transactions()->create([
                'amount' => $totalPrice,
                'type' => 'spend',
                'description' => "Order: {$service->name} x{$request->quantity}",
                'status' => 'completed'
            ]);

            // Calculate profit
            $providerCost = $service->provider_rate
                ? (($service->provider_rate / 1000) * $request->quantity)
                : 0;

            // Create Order
            $order = $user->orders()->create([
                'service_id' => $service->id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'charge' => $totalPrice,
                'status' => 'pending',
                'order_source' => 'web',
                'profit' => $totalPrice - $providerCost,
            ]);

            // Forward to upstream provider
            if ($service->smm_provider_id && $service->provider_service_id) {
                try {
                    $provider = $service->provider;
                    $api = new JapLikeProvider($provider->url, $provider->api_key);
                    $apiRes = $api->addOrder(
                        (int) $service->provider_service_id,
                        $request->link,
                        $request->quantity
                    );

                    $order->update([
                        'provider_order_id' => $apiRes['order_id'] ?? null,
                        'smm_provider_id' => $provider->id,
                        'status' => 'processing',
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Provider Order Failed: " . $e->getMessage(), [
                        'order_id' => $order->id,
                    ]);
                }
            }

            ActivityLog::log('order_placed', "Order #{$order->id} placed ({$service->name})", $user->id);
            DB::commit();

            return redirect()->route('orders.history')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Request a refill for a completed order.
     */
    public function refill(Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return back()->withErrors(['error' => 'Only completed orders can be refilled.']);
        }

        $service = $order->service;
        if (!$service->refill_available) {
            return back()->withErrors(['error' => 'Refill not available for this service.']);
        }

        if ($service->refill_days > 0 && $order->completed_at) {
            $deadline = $order->completed_at->addDays($service->refill_days);
            if (now()->greaterThan($deadline)) {
                return back()->withErrors(['error' => 'Refill period has expired.']);
            }
        }

        // Forward to provider
        if ($order->provider_order_id && $order->smm_provider_id) {
            try {
                $provider = $order->provider;
                $api = new JapLikeProvider($provider->url, $provider->api_key);
                $api->request(['action' => 'refill', 'order' => $order->provider_order_id]);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Refill request failed: ' . $e->getMessage()]);
            }
        }

        ActivityLog::log('order_refill', "Refill requested for Order #{$order->id}", $user->id);

        return back()->with('success', 'Refill request submitted.');
    }
}
