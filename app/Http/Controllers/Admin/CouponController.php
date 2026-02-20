<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('usages')->latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'amount' => 'required|numeric|min:0.01',
            'max_uses' => 'required|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean',
        ]);

        $coupon = Coupon::create($request->only('code', 'amount', 'max_uses', 'expires_at', 'is_active'));
        ActivityLog::log('coupon_created', "Coupon {$coupon->code} created (Amount: {$coupon->amount})");

        return back()->with('success', 'Coupon created successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $code = $coupon->code;
        $coupon->delete();
        ActivityLog::log('coupon_deleted', "Coupon {$code} deleted");

        return back()->with('success', 'Coupon deleted.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', 'Coupon status updated.');
    }
}
