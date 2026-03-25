<?php

namespace App\Console\Commands\Cron;

use App\Models\Order;
use App\Services\Smm\JapLikeProvider;
use Illuminate\Console\Command;

class SyncProviderOrders extends Command
{
    protected $signature = 'smm:sync-orders';
    protected $description = 'Sync order statuses from external providers';

    public function handle()
    {
        $orders = Order::where('status', '!=', 'completed')
            ->where('status', '!=', 'canceled')
            ->whereNotNull('smm_provider_id')
            ->whereNotNull('provider_order_id')
            ->with('provider')
            ->get();

        $this->info("Found {$orders->count()} orders to sync.");

        foreach ($orders as $order) {
            try {
                $provider = $order->provider;
                $api = new JapLikeProvider($provider->url, $provider->api_key);
                
                $statusData = $api->getOrderStatus((int)$order->provider_order_id);
                // Expected: status, start_count, remains

                $newStatus = strtolower($statusData['status'] ?? 'pending');
                
                // Map provider status to our status
                $statusMap = [
                    'inprogress' => 'processing',
                    'in progress' => 'processing',
                    'completed' => 'completed',
                    'canceled' => 'canceled',
                    'partial' => 'partial',
                    'processing' => 'processing',
                    'pending' => 'pending'
                ];

                $order->update([
                    'status' => $statusMap[$newStatus] ?? 'pending',
                    'start_count' => $statusData['start_count'] ?? $order->start_count,
                    'remains' => $statusData['remains'] ?? $order->remains,
                    'api_response' => json_encode($statusData)
                ]);

                $this->info("Order #{$order->id} synced: {$newStatus}");

            } catch (\Exception $e) {
                $this->error("Failed to sync Order #{$order->id}: " . $e->getMessage());
            }
        }
    }
}
