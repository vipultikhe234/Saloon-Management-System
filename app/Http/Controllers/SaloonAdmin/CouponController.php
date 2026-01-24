<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    private function getSaloon()
    {
        return auth()->user()->saloons()->firstOrFail();
    }

    public function index()
    {
        $saloon = $this->getSaloon();
        $coupons = Coupon::where('saloon_id', $saloon->id)
            ->latest()
            ->paginate(10);
        
        return view('saloon-admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('saloon-admin.coupons.create');
    }

    public function store(Request $request)
    {
        $saloon = $this->getSaloon();
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'title' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'min_purchase_amount' => 'nullable|numeric|min:0',
        ]);

        Coupon::create([
            'saloon_id' => $saloon->id,
            'code' => strtoupper($request->code),
            'title' => $request->title,
            'description' => $request->description,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'usage_limit' => $request->usage_limit,
            'usage_per_user' => $request->usage_per_user,
            'min_purchase_amount' => $request->min_purchase_amount,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => true,
        ]);

        return redirect()->route('saloon-admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        if ($coupon->saloon_id !== $this->getSaloon()->id) {
            abort(403);
        }

        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'Coupon status updated.');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->saloon_id !== $this->getSaloon()->id) {
            abort(403);
        }

        $coupon->delete();

        return back()->with('success', 'Coupon deleted.');
    }
}
