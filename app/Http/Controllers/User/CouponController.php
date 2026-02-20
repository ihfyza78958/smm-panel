<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->withErrors(['code' => 'Invalid coupon code.']);
        }

        if (!$coupon->isValid()) {
            return back()->withErrors(['code' => 'This coupon is no longer valid.']);
        }

        $user = auth()->user();

        if ($coupon->usedByUser($user->id)) {
            return back()->withErrors(['code' => 'You have already used this coupon.']);
        }

        DB::transaction(function () use ($coupon, $user) {
            // Add balance
            $user->increment('balance', $coupon->amount);

            // Log transaction
            $user->transactions()->create([
                'amount' => $coupon->amount,
                'type' => 'deposit',
                'payment_method' => 'coupon',
                'description' => "Coupon redeemed: {$coupon->code}",
                'status' => 'completed',
            ]);

            // Record usage
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $user->id,
                'amount' => $coupon->amount,
            ]);

            // Increment used count
            $coupon->increment('used_count');
        });

        ActivityLog::log('coupon_redeemed', "Coupon {$coupon->code} redeemed for {$coupon->amount}", $user->id);

        return back()->with('success', "Coupon redeemed! {$coupon->amount} added to your balance.");
    }
}
