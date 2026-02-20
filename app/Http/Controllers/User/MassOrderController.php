<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MassOrderController extends Controller
{
    public function index()
    {
        return view('user.orders.mass');
    }

    /**
     * Process mass orders in format: service_id|link|quantity (one per line)
     */
    public function store(Request $request)
    {
        $request->validate([
            'orders' => 'required|string',
        ]);

        $user = auth()->user();
        $lines = array_filter(explode("\n", trim($request->orders)));
        $results = [];
        $totalCharge = 0;

        // First pass: validate all orders and calculate total
        $parsedOrders = [];
        foreach ($lines as $index => $line) {
            $parts = array_map('trim', explode('|', $line));
            $lineNum = $index + 1;

            if (count($parts) < 3) {
                $results[] = ['line' => $lineNum, 'status' => 'error', 'message' => 'Invalid format. Expected: service_id|link|quantity'];
                continue;
            }

            [$serviceId, $link, $quantity] = $parts;
            $quantity = (int) $quantity;

            $service = Service::find($serviceId);
            if (!$service || !$service->is_active) {
                $results[] = ['line' => $lineNum, 'status' => 'error', 'message' => "Service #{$serviceId} not found or inactive"];
                continue;
            }

            if ($quantity < $service->min_quantity || $quantity > $service->max_quantity) {
                $results[] = ['line' => $lineNum, 'status' => 'error', 'message' => "Quantity must be {$service->min_quantity}-{$service->max_quantity}"];
                continue;
            }

            $price = $user->getDiscountedPrice($service->price);
            $charge = ($price / 1000) * $quantity;
            $totalCharge += $charge;

            $parsedOrders[] = [
                'line' => $lineNum,
                'service' => $service,
                'link' => $link,
                'quantity' => $quantity,
                'charge' => $charge,
            ];
        }

        // Check total balance
        if ($user->balance < $totalCharge) {
            return back()->withErrors(['orders' => "Insufficient balance. Need NPR {$totalCharge}, have NPR {$user->balance}."]);
        }

        // Process orders
        \DB::beginTransaction();
        try {
            foreach ($parsedOrders as $orderData) {
                $user->decrement('balance', $orderData['charge']);
                $user->increment('spent', $orderData['charge']);

                $user->transactions()->create([
                    'amount' => $orderData['charge'],
                    'type' => 'spend',
                    'description' => "Mass Order: {$orderData['service']->name} x{$orderData['quantity']}",
                    'status' => 'completed',
                ]);

                $providerCost = $orderData['service']->provider_rate
                    ? (($orderData['service']->provider_rate / 1000) * $orderData['quantity'])
                    : 0;

                $order = $user->orders()->create([
                    'service_id' => $orderData['service']->id,
                    'link' => $orderData['link'],
                    'quantity' => $orderData['quantity'],
                    'charge' => $orderData['charge'],
                    'status' => 'pending',
                    'order_source' => 'web',
                    'profit' => $orderData['charge'] - $providerCost,
                ]);

                // Forward to provider
                if ($orderData['service']->smm_provider_id && $orderData['service']->provider_service_id) {
                    try {
                        $provider = $orderData['service']->provider;
                        $api = new \App\Services\Smm\JapLikeProvider($provider->url, $provider->api_key);
                        $apiRes = $api->addOrder(
                            (int) $orderData['service']->provider_service_id,
                            $orderData['link'],
                            $orderData['quantity']
                        );
                        $order->update([
                            'provider_order_id' => $apiRes['order_id'] ?? null,
                            'smm_provider_id' => $provider->id,
                            'status' => 'processing',
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("Mass Order API Failed: " . $e->getMessage());
                    }
                }

                $results[] = [
                    'line' => $orderData['line'],
                    'status' => 'success',
                    'message' => "Order #{$order->id} placed",
                    'order_id' => $order->id,
                ];
            }

            \DB::commit();
            ActivityLog::log('mass_order', count($parsedOrders) . " mass orders placed", $user->id);
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['orders' => 'Mass order failed: ' . $e->getMessage()]);
        }

        return back()->with('success', count($parsedOrders) . ' orders placed successfully.')
            ->with('results', $results);
    }
}
