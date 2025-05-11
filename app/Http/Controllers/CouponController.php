<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper(Str::random(8)),
            'discount_percentage' => $request->discount_percentage,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon status updated successfully!');
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        if (!$coupon->isValid()) {
            return back()->with('error', 'This coupon is no longer valid.');
        }

        session(['applied_coupon' => $coupon]);
        return back()->with('success', 'Coupon applied successfully!');
    }

    public function remove()
    {
        session()->forget('applied_coupon');
        return back()->with('success', 'Coupon removed successfully!');
    }
}
