<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\User\CouponController;
use App\Http\Controllers\User\MassOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\BlogPost;
use App\Models\Announcement;
use App\Models\Setting;

// ── Public Routes ──
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/smm', function () {
    $categories = Category::with(['services' => function($q) {
        $q->where('is_active', true)->limit(5);
    }])->where('is_active', true)->orderBy('sort_order')->get();
    
    $latest_posts = BlogPost::where('is_published', true)->latest()->limit(6)->get();
    $announcements = Announcement::getActive();
    
    return view('welcome', compact('categories', 'latest_posts', 'announcements'));
})->name('smm');

Route::get('/blog', function () {
    $posts = BlogPost::where('is_published', true)->latest()->paginate(12);
    return view('blog.index', compact('posts'));
})->name('blog.index');

Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/terms', [App\Http\Controllers\PageController::class, 'terms'])->name('page.terms');
Route::get('/privacy', [App\Http\Controllers\PageController::class, 'privacy'])->name('page.privacy');
Route::get('/services', [\App\Http\Controllers\Guest\ServiceController::class, 'index'])->name('guest.services');

// ── Authenticated User Routes ──
Route::middleware(['auth', 'verified', 'banned'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        $categories = Category::with(['services' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->orderBy('sort_order')->get();
        
        $user = auth()->user();
        $recentOrders = $user->orders()->with('service')->latest()->limit(5)->get();
        $announcements = Announcement::getActive();
        
        return view('dashboard', compact('categories', 'recentOrders', 'announcements'));
    })->name('dashboard');

    // Orders
    Route::get('/orders/new', [OrderController::class, 'index'])->name('orders.new');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::post('/orders/{order}/refill', [OrderController::class, 'refill'])->name('orders.refill');

    // Mass Orders
    Route::get('/orders/mass', [MassOrderController::class, 'index'])->name('orders.mass');
    Route::post('/orders/mass', [MassOrderController::class, 'store'])->name('orders.mass.store');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::get('/payment/success/{gateway}', [WalletController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure/{gateway}', [WalletController::class, 'failure'])->name('payment.failure');

    // Payment Gateways (eSewa & Khalti)
    Route::post('/payment/esewa/process', [\App\Http\Controllers\PaymentController::class, 'esewaProcess'])->name('payment.esewa.process');
    Route::get('/payment/esewa/success', [\App\Http\Controllers\PaymentController::class, 'esewaSuccess'])->name('payment.esewa.success');
    Route::get('/payment/esewa/failure', [\App\Http\Controllers\PaymentController::class, 'esewaFailure'])->name('payment.esewa.failure');

    Route::post('/payment/khalti/process', [\App\Http\Controllers\PaymentController::class, 'khaltiProcess'])->name('payment.khalti.process');
    Route::get('/payment/khalti/callback', [\App\Http\Controllers\PaymentController::class, 'khaltiCallback'])->name('payment.khalti.callback');

    // Manual Topup
    Route::get('/user/topup', [\App\Http\Controllers\TopupController::class, 'create'])->name('user.topup.create');
    Route::post('/user/topup', [\App\Http\Controllers\TopupController::class, 'store'])->name('user.topup.store');
    Route::get('/user/invoice/{transaction}', [\App\Http\Controllers\TopupController::class, 'show'])->name('user.topup.show');
    Route::put('/user/invoice/{transaction}', [\App\Http\Controllers\TopupController::class, 'update'])->name('user.topup.update');

    // Coupons
    Route::post('/coupon/redeem', [CouponController::class, 'redeem'])->name('coupon.redeem');

    // Tickets
    Route::resource('tickets', TicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');

    // Profile & API Key
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/api-key', [ProfileController::class, 'generateApiKey'])->name('profile.api-key');
});

// ── Admin Routes ──
Route::middleware(['auth', 'verified', 'banned', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/login', [\App\Http\Controllers\Admin\UserController::class, 'loginAsUser'])->name('users.login');
    Route::patch('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'toggleBan'])->name('users.ban');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');

    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::patch('/categories/{category}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('categories.toggle');

    // Services
    Route::get('/services', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [\App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [\App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('services.destroy');
    Route::patch('/services/{service}/toggle', [\App\Http\Controllers\Admin\ServiceController::class, 'toggle'])->name('services.toggle');
    Route::post('/services/bulk-update-prices', [\App\Http\Controllers\Admin\ServiceController::class, 'bulkUpdatePrices'])->name('services.bulk-update-prices');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/bulk-cancel', [\App\Http\Controllers\Admin\OrderController::class, 'bulkCancel'])->name('orders.bulk-cancel');

    // Providers
    Route::resource('providers', \App\Http\Controllers\Admin\ProviderController::class)->except(['show']);

    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions/{transaction}/approve', [\App\Http\Controllers\Admin\TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [\App\Http\Controllers\Admin\TransactionController::class, 'reject'])->name('transactions.reject');
    
    // Tickets
    Route::controller(\App\Http\Controllers\Admin\TicketController::class)->prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{ticket}', 'show')->name('show');
        Route::post('/{ticket}/reply', 'reply')->name('reply');
        Route::patch('/{ticket}/close', 'close')->name('close');
    });

    // Coupons
    Route::get('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
    Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::patch('/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');

    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\Admin\SettingController::class, 'announcements'])->name('announcements.index');
    Route::post('/announcements', [\App\Http\Controllers\Admin\SettingController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [\App\Http\Controllers\Admin\SettingController::class, 'deleteAnnouncement'])->name('announcements.destroy');

    // Blogs
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class)->except(['show']);

    // Activity Logs
    Route::get('/logs', function () {
        $logs = \App\Models\ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.logs.index', compact('logs'));
    })->name('logs.index');
});

// Social Auth
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
