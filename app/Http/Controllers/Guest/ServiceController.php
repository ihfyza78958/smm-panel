<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->whereHas('services', fn($q) => $q->where('is_active', true))
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        $query = Service::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $services = $query->orderBy('price')->paginate(50);

        return view('services.index', compact('categories', 'services'));
    }
}
