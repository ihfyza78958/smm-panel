<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'orders' => Order::count(),
            'revenue' => Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'profit' => Order::whereIn('status', ['completed', 'processing', 'in_progress'])->sum('profit'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Transaction::where('type', 'deposit')->where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'active_services' => \App\Models\Service::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
