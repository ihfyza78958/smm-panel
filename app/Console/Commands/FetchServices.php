<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Service;
use App\Models\SmmProvider;
use App\Services\Smm\JapLikeProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FetchServices extends Command
{
    protected $signature = 'smm:fetch-services {provider_id?}';
    protected $description = 'Fetch and sync services from SMM Provider';

    public function handle()
    {
        $providerId = $this->argument('provider_id');

        if ($providerId) {
            $providers = SmmProvider::where('id', $providerId)->get();
        } else {
            $providers = SmmProvider::where('is_active', true)->get();
        }

        foreach ($providers as $provider) {
            $this->info("Fetching services for: {$provider->domain}");
            
            try {
                $api = new JapLikeProvider($provider->url, $provider->api_key);
                $services = $api->services(); // Assumes returns array of services

                $this->info("Found " . count($services) . " services.");
                
                $bar = $this->output->createProgressBar(count($services));
                $bar->start();

                foreach ($services as $serviceData) {
                    // API sends: service, name, type, category, rate, min, max
                    // Map category
                    $categoryName = $serviceData['category'];
                    $category = Category::firstOrCreate(
                        ['name' => $categoryName],
                        [
                            'slug' => Str::slug($categoryName),
                            'sort_order' => 99,
                            'is_active' => true
                        ]
                    );

                    // Create/Update Service
                    // Rate logic: Provider Rate * Multiplier? For now, keep price = rate (or add markup later)
                    // Usually we set price > provider_rate. Let's set a default markup of 20% if new.
                    
                    $providerRate = (float) $serviceData['rate'];
                    $conversionRate = (float) ($provider->conversion_rate ?? 1);
                    if ($conversionRate <= 0) {
                        $conversionRate = 1;
                    }
                    $providerRate = round($providerRate * $conversionRate, 6);
                    
                    // Auto markup: Apply profit margin (default 20%)
                    $existingService = Service::where('smm_provider_id', $provider->id)
                        ->where('provider_service_id', $serviceData['service'])
                        ->first();
                    
                    // Keep existing margin if already set, otherwise default 20%
                    $margin = $existingService ? $existingService->profit_margin : 20;
                    $price = $providerRate * (1 + ($margin / 100));

                    Service::updateOrCreate(
                        [
                            'smm_provider_id' => $provider->id,
                            'provider_service_id' => $serviceData['service'],
                        ],
                        [
                            'category_id' => $category->id,
                            'name' => $serviceData['name'],
                            'type' => $serviceData['type'] ?? 'default',
                            'provider_rate' => $providerRate,
                            'price' => $price, // WARN: Admin should adjust this!
                            'min_quantity' => $serviceData['min'],
                            'max_quantity' => $serviceData['max'],
                            'is_active' => true, // Or default to false for review
                        ]
                    );

                    $bar->advance();
                }

                $bar->finish();
                $this->newLine();
                $this->info("Completed {$provider->domain}");

            } catch (\Exception $e) {
                $this->error("Failed to fetch for {$provider->domain}: " . $e->getMessage());
            }
        }
    }
}
