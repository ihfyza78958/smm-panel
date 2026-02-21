<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Transaction;
use App\Models\SmmProvider;
use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Core stats
        $totalRevenue = Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $lastMonthRevenue = Transaction::where('type', 'deposit')->where('status', 'completed')
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->sum('amount');
        $thisMonthRevenue = Transaction::where('type', 'deposit')->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))->sum('amount');
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : ($thisMonthRevenue > 0 ? 100 : 0);

        $stats = [
            'users' => User::count(),
            'orders' => Order::count(),
            'revenue' => $totalRevenue,
            'revenue_growth' => $revenueGrowth,
            'orders_pending' => Order::where('status', 'pending')->count(),
            'profit' => Order::whereIn('status', ['completed', 'processing', 'in_progress'])->sum('profit'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Transaction::where('type', 'deposit')->where('status', 'completed')->whereDate('created_at', today())->sum('amount'),
            'active_services' => Service::where('is_active', true)->count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
        ];

        // Revenue chart data (last 7 days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('M d'),
                'revenue' => Transaction::where('type', 'deposit')
                    ->where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('amount'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }

        // Provider statuses
        $providers = SmmProvider::select('id', 'domain', 'url', 'is_active', 'balance', 'currency')
            ->withCount('services')
            ->get();

        // Recent orders
        $recentOrders = Order::with(['user', 'service'])
            ->latest()
            ->take(5)
            ->get();

        // Top services by order count
        $topServices = Service::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'providers', 'recentOrders', 'topServices'));
    }
}
