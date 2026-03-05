<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\SmmProvider;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category', 'provider');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = Category::orderBy('sort_order')->get();
        $services = $query->orderBy('category_id')->orderBy('sort_order')->paginate(50);

        return view('admin.services.index', compact('categories', 'services'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        $providers = SmmProvider::where('is_active', true)->get();
        return view('admin.services.create', compact('categories', 'providers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:default,package,custom_comments,subscription',
            'price' => 'required|numeric|min:0',
            'min_quantity' => 'required|integer|min:1',
            'max_quantity' => 'required|integer|gte:min_quantity',
            'smm_provider_id' => 'nullable|exists:smm_providers,id',
            'provider_service_id' => 'nullable|string',
            'provider_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'drip_feed_active' => 'boolean',
            'refill_available' => 'boolean',
            'cancel_allowed' => 'boolean',
            'refill_days' => 'nullable|integer|min:0',
            'profit_margin' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $service = Service::create($request->all());
        ActivityLog::log('service_created', "Service #{$service->id} ({$service->name}) created");

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $categories = Category::orderBy('sort_order')->get();
        $providers = SmmProvider::where('is_active', true)->get();
        return view('admin.services.edit', compact('service', 'categories', 'providers'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:default,package,custom_comments,subscription',
            'price' => 'required|numeric|min:0',
            'min_quantity' => 'required|integer|min:1',
            'max_quantity' => 'required|integer|gte:min_quantity',
            'smm_provider_id' => 'nullable|exists:smm_providers,id',
            'provider_service_id' => 'nullable|string',
            'provider_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'drip_feed_active' => 'boolean',
            'refill_available' => 'boolean',
            'cancel_allowed' => 'boolean',
            'refill_days' => 'nullable|integer|min:0',
            'profit_margin' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $service->update($request->all());
        ActivityLog::log('service_updated', "Service #{$service->id} ({$service->name}) updated");

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $name = $service->name;
        $service->delete();
        ActivityLog::log('service_deleted', "Service {$name} deleted");

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Toggle service active status.
     */
    public function toggle(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        $status = $service->is_active ? 'enabled' : 'disabled';
        ActivityLog::log('service_toggled', "Service #{$service->id} {$status}");

        return back()->with('success', "Service {$status}.");
    }

    /**
     * Bulk update prices by margin percentage.
     */
    public function bulkUpdatePrices(Request $request)
    {
        $request->validate([
            'margin' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $query = Service::with('provider')->whereNotNull('provider_rate');
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $margin = $request->margin / 100;
        $updated = 0;

        $query->chunk(100, function ($services) use ($margin, &$updated) {
            foreach ($services as $service) {
                $baseRate = (float) $service->provider_rate;
                $newPrice = $baseRate * (1 + $margin);
                $service->update([
                    'price' => round($newPrice, 4),
                    'profit_margin' => $margin * 100,
                ]);
                $updated++;
            }
        });

        ActivityLog::log('bulk_price_update', "Updated {$updated} service prices with {$request->margin}% margin");

        return back()->with('success', "{$updated} services updated with {$request->margin}% margin.");
    }
}
