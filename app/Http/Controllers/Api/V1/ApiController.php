<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\Smm\JapLikeProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Validate API key and return authenticated user.
     */
    private function getUser(Request $request): User
    {
        $key = $request->input('key');
        if (!$key) {
            abort(response()->json(['error' => 'API Key is required'], 401));
        }

        $user = User::where('api_key', $key)->first();
        if (!$user) {
            abort(response()->json(['error' => 'Invalid API Key'], 401));
        }

        if ($user->is_banned) {
            abort(response()->json(['error' => 'Account suspended'], 403));
        }

        return $user;
    }

    /**
     * Main handler — route by action parameter.
     */
    public function handle(Request $request)
    {
        $action = $request->input('action');

        return match ($action) {
            'services' => $this->services($request),
            'add' => $this->addOrder($request),
            'status' => $this->status($request),
            'balance' => $this->balance($request),
            'refill' => $this->refill($request),
            'cancel' => $this->cancel($request),
            default => response()->json(['error' => 'Invalid action'], 400),
        };
    }

    /**
     * List all available services.
     */
    public function services(Request $request)
    {
        $user = null;
        if ($request->input('key')) {
            try {
                $user = $this->getUser($request);
            } catch (\Exception $e) {
                // Allow unauthenticated listing
            }
        }

        $services = Service::where('is_active', true)
            ->with('category')
            ->orderBy('category_id')
            ->get()
            ->map(function ($service) use ($user) {
                $rate = $service->price;
                if ($user && $user->discount_percentage > 0) {
                    $rate = $user->getDiscountedPrice($rate);
                }

                return [
                    'service' => $service->id,
                    'name' => $service->name,
                    'type' => $service->type,
                    'category' => $service->category->name ?? 'Unknown',
                    'rate' => number_format($rate, 4, '.', ''),
                    'min' => $service->min_quantity,
                    'max' => $service->max_quantity,
                    'dripfeed' => $service->drip_feed_active,
                    'refill' => $service->refill_available,
                    'cancel' => $service->cancel_allowed,
                    'description' => $service->description ?? '',
                ];
            });

        return response()->json($services);
    }

    /**
     * Place a new order via API.
     */
    public function addOrder(Request $request)
    {
        $user = $this->getUser($request);

        $validator = Validator::make($request->all(), [
            'service' => 'required|exists:services,id',
            'link' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'runs' => 'nullable|integer|min:1',
            'interval' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $service = Service::find($request->service);

        if (!$service->is_active) {
            return response()->json(['error' => 'Service is not available'], 400);
        }

        if ($request->quantity < $service->min_quantity || $request->quantity > $service->max_quantity) {
            return response()->json([
                'error' => "Quantity must be between {$service->min_quantity} and {$service->max_quantity}"
            ], 400);
        }

        // Calculate charge with user discount
        $price = $user->getDiscountedPrice($service->price);
        $charge = ($price / 1000) * $request->quantity;

        if ($user->balance < $charge) {
            return response()->json(['error' => 'Not enough funds'], 400);
        }

        try {
            DB::beginTransaction();

            // Deduct balance
            $user->decrement('balance', $charge);
            $user->increment('spent', $charge);

            // Create transaction log
            $user->transactions()->create([
                'amount' => $charge,
                'type' => 'spend',
                'description' => "API Order: {$service->name} x{$request->quantity}",
                'status' => 'completed',
            ]);

            // Create order
            $isDripFeed = $request->filled('runs') && $request->filled('interval');
            $providerCost = $service->provider_rate ? (($service->provider_rate / 1000) * $request->quantity) : 0;
            $order = $user->orders()->create([
                'service_id' => $service->id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'charge' => $charge,
                'status' => 'pending',
                'order_source' => 'api',
                'is_drip_feed' => $isDripFeed,
                'drip_feed_runs' => $isDripFeed ? $request->runs : null,
                'drip_feed_interval' => $isDripFeed ? $request->interval : null,
                'profit' => $charge - $providerCost,
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
                    \Log::error("API Provider Order Failed: " . $e->getMessage(), [
                        'order_id' => $order->id,
                        'service_id' => $service->id,
                    ]);
                }
            }

            ActivityLog::log('api_order', "API Order #{$order->id} placed", $user->id);
            DB::commit();

            return response()->json(['order' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get order status.
     */
    public function status(Request $request)
    {
        $user = $this->getUser($request);

        $validator = Validator::make($request->all(), [
            'order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $order = Order::where('id', $request->order)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'charge' => $order->charge,
            'start_count' => $order->start_count,
            'status' => $order->status,
            'remains' => $order->remains,
            'currency' => 'NPR',
        ]);
    }

    /**
     * Get user balance.
     */
    public function balance(Request $request)
    {
        $user = $this->getUser($request);

        return response()->json([
            'balance' => $user->balance,
            'currency' => 'NPR',
        ]);
    }

    /**
     * Request a refill for an order.
     */
    public function refill(Request $request)
    {
        $user = $this->getUser($request);

        $validator = Validator::make($request->all(), [
            'order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $order = Order::where('id', $request->order)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found or not eligible for refill'], 404);
        }

        $service = $order->service;
        if (!$service->refill_available) {
            return response()->json(['error' => 'Refill not available for this service'], 400);
        }

        // Check if within refill window
        if ($service->refill_days > 0 && $order->completed_at) {
            $refillDeadline = $order->completed_at->addDays($service->refill_days);
            if (now()->greaterThan($refillDeadline)) {
                return response()->json(['error' => 'Refill period has expired'], 400);
            }
        }

        // Forward refill to provider if applicable
        if ($order->provider_order_id && $order->smm_provider_id) {
            try {
                $provider = $order->provider;
                $api = new JapLikeProvider($provider->url, $provider->api_key);
                $res = $api->request([
                    'action' => 'refill',
                    'order' => $order->provider_order_id,
                ]);

                return response()->json([
                    'refill' => $res['refill'] ?? $order->id,
                    'message' => 'Refill request submitted',
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Refill request failed'], 500);
            }
        }

        return response()->json(['refill' => $order->id, 'message' => 'Refill request submitted']);
    }

    /**
     * Request cancellation of an order.
     */
    public function cancel(Request $request)
    {
        $user = $this->getUser($request);

        $validator = Validator::make($request->all(), [
            'order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $order = Order::where('id', $request->order)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found or cannot be cancelled'], 404);
        }

        $service = $order->service;
        if (!$service->cancel_allowed) {
            return response()->json(['error' => 'Cancellation not allowed for this service'], 400);
        }

        try {
            DB::beginTransaction();

            // Refund the charge
            $user->increment('balance', $order->charge);
            $user->decrement('spent', $order->charge);

            $user->transactions()->create([
                'amount' => $order->charge,
                'type' => 'refund',
                'description' => "Cancelled Order #{$order->id}",
                'status' => 'completed',
            ]);

            $order->update(['status' => 'canceled']);

            ActivityLog::log('order_cancel', "Order #{$order->id} cancelled via API", $user->id);
            DB::commit();

            return response()->json(['message' => 'Order cancelled and refunded']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Cancellation failed'], 500);
        }
    }
}
