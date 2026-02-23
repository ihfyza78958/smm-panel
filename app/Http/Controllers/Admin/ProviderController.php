<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmmProvider;
use App\Models\Service;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Services\Smm\JapLikeProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = SmmProvider::withCount('services')->get();
        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255',
            'url' => 'required|url',
            'api_key' => 'required|string',
            'currency' => 'required|string|max:3',
        ]);

        try {
            $api = new JapLikeProvider($validated['url'], $validated['api_key']);
            $validated['balance'] = $api->getBalance();
            $validated['is_active'] = true;

            // Count total services available from this provider
            $services = $api->services();
            $validated['total_services'] = is_array($services) ? count($services) : 0;
        } catch (\Exception $e) {
            $validated['balance'] = 0;
        }

        SmmProvider::create($validated);
        ActivityLog::log('provider_created', "Provider {$validated['domain']} added");

        return redirect()->route('admin.providers.index')->with('success', 'Provider added successfully.');
    }

    public function edit(SmmProvider $provider)
    {
        return view('admin.providers.edit', compact('provider'));
    }

    public function update(Request $request, SmmProvider $provider)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255',
            'url' => 'required|url',
            'api_key' => 'required|string',
            'currency' => 'required|string|max:3',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('api_key')) {
            try {
                $api = new JapLikeProvider($validated['url'], $validated['api_key']);
                $validated['balance'] = $api->getBalance();
            } catch (\Exception $e) {
                // Keep old balance
            }
        }

        $provider->update($validated);
        ActivityLog::log('provider_updated', "Provider {$provider->domain} updated");

        return redirect()->route('admin.providers.index')->with('success', 'Provider updated successfully.');
    }

    public function destroy(SmmProvider $provider)
    {
        $domain = $provider->domain;
        $provider->delete();
        ActivityLog::log('provider_deleted', "Provider {$domain} deleted");

        return redirect()->route('admin.providers.index')->with('success', 'Provider deleted successfully.');
    }

    /**
     * Refresh provider balance via API.
     */
    public function syncBalance(SmmProvider $provider)
    {
        try {
            $api = new JapLikeProvider($provider->url, $provider->api_key);
            $balance = $api->getBalance();
            $provider->update(['balance' => $balance]);

            return back()->with('success', "Balance updated: {$balance} {$provider->currency}");
        } catch (\Exception $e) {
            return back()->with('error', "Failed to fetch balance: {$e->getMessage()}");
        }
    }

    /**
     * Show the import/sync services page for a provider.
     */
    public function showServices(SmmProvider $provider)
    {
        $categories = Category::orderBy('sort_order')->get();
        $importedIds = Service::where('smm_provider_id', $provider->id)
            ->pluck('provider_service_id')
            ->toArray();

        $localServices = Service::where('smm_provider_id', $provider->id)
            ->with('category')
            ->get()
            ->keyBy('provider_service_id');

        return view('admin.providers.services', compact('provider', 'categories', 'importedIds', 'localServices'));
    }

    /**
     * Fetch services from provider API (AJAX).
     */
    public function fetchServices(SmmProvider $provider)
    {
        try {
            $api = new JapLikeProvider($provider->url, $provider->api_key);
            $services = $api->services();

            if (!is_array($services)) {
                return response()->json(['error' => 'Invalid response from provider API'], 422);
            }

            // Update provider stats
            $provider->update([
                'total_services' => count($services),
                'balance' => $api->getBalance(),
                'last_synced_at' => now(),
            ]);

            // Get already imported service IDs for this provider
            $importedIds = Service::where('smm_provider_id', $provider->id)
                ->pluck('provider_service_id')
                ->toArray();

            // Get local services keyed by provider_service_id for price comparison
            $localServices = Service::where('smm_provider_id', $provider->id)
                ->get()
                ->keyBy('provider_service_id');

            // Organize services by category
            $organized = [];
            foreach ($services as $svc) {
                $serviceId = (string) $svc['service'];
                $category = $svc['category'] ?? 'Uncategorized';
                
                if (!isset($organized[$category])) {
                    $organized[$category] = [];
                }

                $local = $localServices[$serviceId] ?? null;
                $svc['is_imported'] = in_array($serviceId, $importedIds);
                $svc['local_price'] = $local ? $local->price : null;
                $svc['local_provider_rate'] = $local ? $local->provider_rate : null;
                $svc['local_id'] = $local ? $local->id : null;
                $svc['price_changed'] = $local && (float) $local->provider_rate !== (float) $svc['rate'];

                $organized[$category][] = $svc;
            }

            return response()->json([
                'services' => $organized,
                'total' => count($services),
                'imported' => count($importedIds),
                'balance' => $provider->balance,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Import selected services from provider.
     */
    public function importServices(Request $request, SmmProvider $provider)
    {
        $request->validate([
            'services' => 'required|array|min:1',
            'services.*.id' => 'required',
            'services.*.name' => 'required|string',
            'services.*.category_name' => 'required|string',
            'services.*.category_id' => 'nullable|integer',
            'services.*.rate' => 'required|numeric',
            'services.*.min' => 'required|integer',
            'services.*.max' => 'required|integer',
            'services.*.type' => 'nullable|string',
            'services.*.refill' => 'nullable',
            'services.*.cancel' => 'nullable',
            'services.*.dripfeed' => 'nullable',
            'services.*.description' => 'nullable|string',
            'profit_margin' => 'required|numeric|min:0',
        ]);

        $margin = $request->profit_margin / 100;
        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($request->services as $svc) {
                $serviceId = (string) $svc['id'];

                // Check if already imported
                $exists = Service::where('smm_provider_id', $provider->id)
                    ->where('provider_service_id', $serviceId)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Find or create category
                $categoryId = $svc['category_id'] ?? null;
                if (!$categoryId) {
                    $categoryName = $svc['category_name'];
                    $category = Category::firstOrCreate(
                        ['slug' => \Illuminate\Support\Str::slug($categoryName)],
                        [
                            'name' => $categoryName,
                            'sort_order' => Category::max('sort_order') + 1,
                            'is_active' => true,
                        ]
                    );
                    $categoryId = $category->id;
                }

                $rate = (float) $svc['rate'];
                $price = round($rate * (1 + $margin), 4);

                // Map type
                $type = 'default';
                if (isset($svc['type'])) {
                    $typeMap = [
                        'Default' => 'default',
                        'Package' => 'package',
                        'Custom Comments' => 'custom_comments',
                        'Subscriptions' => 'subscriptions',
                    ];
                    $type = $typeMap[$svc['type']] ?? 'default';
                }

                Service::create([
                    'category_id' => $categoryId,
                    'name' => $svc['name'],
                    'description' => $svc['description'] ?? null,
                    'type' => $type,
                    'price' => $price,
                    'min_quantity' => (int) $svc['min'],
                    'max_quantity' => (int) $svc['max'],
                    'smm_provider_id' => $provider->id,
                    'provider_service_id' => $serviceId,
                    'provider_rate' => $rate,
                    'is_active' => true,
                    'drip_feed_active' => (bool) ($svc['dripfeed'] ?? false),
                    'refill_available' => (bool) ($svc['refill'] ?? false),
                    'cancel_allowed' => (bool) ($svc['cancel'] ?? false),
                    'profit_margin' => $request->profit_margin,
                    'sort_order' => 0,
                ]);

                $imported++;
            }

            // Update provider imported count
            $provider->update([
                'imported_services' => Service::where('smm_provider_id', $provider->id)->count(),
                'last_synced_at' => now(),
            ]);

            DB::commit();

            ActivityLog::log('services_imported', "Imported {$imported} services from {$provider->domain} (skipped {$skipped})");

            return response()->json([
                'success' => true,
                'imported' => $imported,
                'skipped' => $skipped,
                'message' => "Successfully imported {$imported} services" . ($skipped ? " ({$skipped} already existed)" : ""),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Sync existing imported services — update prices, min, max, availability.
     */
    public function syncServices(Request $request, SmmProvider $provider)
    {
        try {
            $api = new JapLikeProvider($provider->url, $provider->api_key);
            $remoteServices = $api->services();

            if (!is_array($remoteServices)) {
                return response()->json(['error' => 'Invalid response from provider API'], 422);
            }

            // Index remote services by ID
            $remoteById = [];
            foreach ($remoteServices as $svc) {
                $remoteById[(string) $svc['service']] = $svc;
            }

            // Get local services for this provider
            $localServices = Service::where('smm_provider_id', $provider->id)->get();

            $updated = 0;
            $disabled = 0;
            $unchanged = 0;

            DB::beginTransaction();

            foreach ($localServices as $local) {
                $remote = $remoteById[$local->provider_service_id] ?? null;

                if (!$remote) {
                    // Service no longer exists on provider — disable it
                    if ($local->is_active) {
                        $local->update(['is_active' => false]);
                        $disabled++;
                    }
                    continue;
                }

                $changes = [];
                $remoteRate = (float) $remote['rate'];

                if ((float) $local->provider_rate !== $remoteRate) {
                    $changes['provider_rate'] = $remoteRate;
                    // Recalculate price using current margin
                    $margin = ($local->profit_margin ?? 20) / 100;
                    $changes['price'] = round($remoteRate * (1 + $margin), 4);
                }

                if ((int) $local->min_quantity !== (int) $remote['min']) {
                    $changes['min_quantity'] = (int) $remote['min'];
                }

                if ((int) $local->max_quantity !== (int) $remote['max']) {
                    $changes['max_quantity'] = (int) $remote['max'];
                }

                if (isset($remote['refill'])) {
                    $changes['refill_available'] = (bool) $remote['refill'];
                }
                if (isset($remote['cancel'])) {
                    $changes['cancel_allowed'] = (bool) $remote['cancel'];
                }
                if (isset($remote['dripfeed'])) {
                    $changes['drip_feed_active'] = (bool) $remote['dripfeed'];
                }

                if (!empty($changes)) {
                    $local->update($changes);
                    $updated++;
                } else {
                    $unchanged++;
                }
            }

            // Update provider stats
            $provider->update([
                'total_services' => count($remoteServices),
                'imported_services' => Service::where('smm_provider_id', $provider->id)->count(),
                'balance' => $api->getBalance(),
                'last_synced_at' => now(),
            ]);

            DB::commit();

            ActivityLog::log('services_synced', "Synced services from {$provider->domain}: {$updated} updated, {$disabled} disabled, {$unchanged} unchanged");

            return response()->json([
                'success' => true,
                'updated' => $updated,
                'disabled' => $disabled,
                'unchanged' => $unchanged,
                'message' => "Sync complete: {$updated} updated, {$disabled} disabled, {$unchanged} unchanged",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
