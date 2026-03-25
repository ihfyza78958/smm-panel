<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SmmProvider;
use App\Models\Service;
use App\Services\Smm\JapLikeProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;

class SyncProviderServices extends Command
{
    protected $signature = 'smm:sync-services';
    protected $description = 'Automatically sync all imported services from active providers (Update prices and delete dead services)';

    public function handle()
    {
        $providers = SmmProvider::where('is_active', true)->get();

        $this->info("Starting automatic service sync for {$providers->count()} active providers...");
        
        foreach ($providers as $provider) {
            $this->info("Syncing provider: {$provider->domain}");
            
            try {
                $api = new JapLikeProvider($provider->url, $provider->api_key);
                $remoteServices = $api->services();

                if (!is_array($remoteServices)) {
                    $this->error("Invalid response from provider {$provider->domain}");
                    continue;
                }

                // Index remote services by ID
                $remoteById = [];
                foreach ($remoteServices as $svc) {
                    $remoteById[(string) $svc['service']] = $svc;
                }

                // Get local services for this provider
                $localServices = Service::where('smm_provider_id', $provider->id)->get();

                $updated = 0;
                $deleted = 0;
                $unchanged = 0;
                $newAdded = 0;

                DB::beginTransaction();
                
                $existingProviderServiceIds = $localServices->pluck('provider_service_id')->toArray();

                foreach ($localServices as $local) {
                    $remote = $remoteById[$local->provider_service_id] ?? null;

                    if (!$remote) {
                        // Service no longer exists on provider — Disable it, mark as dead, but KEEP in DB
                        if ($local->is_active) {
                            $local->update([
                                'is_active' => false,
                                'description' => '[DEAD] ' . $local->description // Tag it so admin can find it
                            ]);
                            $deleted++;
                        }
                        continue;
                    }

                    $changes = [];
                    $remoteRate = (float) $remote['rate'];
                    $convertedRemoteRate = $this->convertRate($provider, $remoteRate);

                    // Check if price changed
                    if ($this->hasRateChanged((float) $local->provider_rate, $convertedRemoteRate)) {
                        $changes['provider_rate'] = $convertedRemoteRate;
                        
                        // If using percentage markup, auto-adjust the selling price
                        if ($local->profit_margin > 0) {
                            $changes['price'] = $convertedRemoteRate + ($convertedRemoteRate * ($local->profit_margin / 100));
                        }
                    }

                    if ($local->min_quantity != $remote['min']) {
                        $changes['min_quantity'] = $remote['min'];
                    }

                    if ($local->max_quantity != $remote['max']) {
                        $changes['max_quantity'] = $remote['max'];
                    }

                    if (!empty($changes)) {
                        $local->update($changes);
                        $updated++;
                    } else {
                        $unchanged++;
                    }
                }
                
                // NOW auto-import NEW services that SMM Nepal added
                foreach ($remoteServices as $svc) {
                    $serviceId = (string) $svc['service'];
                    
                    if (!in_array($serviceId, $existingProviderServiceIds)) {
                        // It's a brand new service! Let's create a category for it if needed
                        $categoryName = $svc['category'] ?? 'Uncategorized';
                        // Need to create slug for new categories safely
                        $category = \App\Models\Category::where('name', $categoryName)->first();
                        if (!$category) {
                            $category = \App\Models\Category::create([
                                'name' => $categoryName,
                                'slug' => \Illuminate\Support\Str::slug($categoryName) . '-' . time(),
                                'is_active' => true,
                                'sort_order' => 0
                            ]);
                        }
                        
                        $remoteRate = (float) $svc['rate'];
                        $convertedRate = $this->convertRate($provider, $remoteRate);
                        $margin = 20; // Default 20% margin for brand new auto-imported services
                        $sellingPrice = round($convertedRate * (1 + ($margin / 100)), 4);
                        
                        \App\Models\Service::create([
                            'category_id' => $category->id,
                            'name' => $svc['name'],
                            'description' => $svc['description'] ?? null,
                            'type' => 'Default',
                            'price' => $sellingPrice,
                            'min_quantity' => (int) $svc['min'],
                            'max_quantity' => (int) $svc['max'],
                            'smm_provider_id' => $provider->id,
                            'provider_service_id' => $serviceId,
                            'provider_rate' => $convertedRate,
                            'is_active' => true, // AUTO PUBLISH so users see it immediately
                            'drip_feed_active' => (bool) ($svc['dripfeed'] ?? false),
                            'refill_available' => (bool) ($svc['refill'] ?? false),
                            'cancel_allowed' => (bool) ($svc['cancel'] ?? false),
                            'profit_margin' => $margin,
                            'sort_order' => 0,
                        ]);
                        
                        $newAdded++;
                    }
                }

                $provider->update(['last_synced_at' => now()]);
                DB::commit();

                $msg = "Auto-Synced {$provider->domain}: {$updated} updated, {$deleted} dead services removed, {$newAdded} NEW services added & published, {$unchanged} unchanged.";
                ActivityLog::log('services_auto_synced', $msg);
                $this->info($msg);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to auto-sync services for {$provider->domain}: " . $e->getMessage());
                $this->error("Error syncing {$provider->domain}: " . $e->getMessage());
            }
        }
        
        $this->info('All provider services sync completed.');
    }
    
    private function convertRate(SmmProvider $provider, float $rate): float
    {
        $conversionRate = (float) ($provider->conversion_rate ?? 1);
        if ($conversionRate <= 0) {
            $conversionRate = 1;
        }

        return round($rate * $conversionRate, 4);
    }

    private function hasRateChanged(float $localRate, float $remoteConvertedRate): bool
    {
        return abs(round($localRate, 4) - round($remoteConvertedRate, 4)) >= 0.0001;
    }
}
