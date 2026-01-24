<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Saloon $saloon, Request $request)
    {
        $selectedServiceId = $request->query('service_id');
        
        $services = Service::where('saloon_id', $saloon->id)
            ->where('is_active', true)
            ->get();

        $staff = Staff::where('saloon_id', $saloon->id)
            ->where('is_active', true)
            ->get();

        return view('user.booking.create', compact('saloon', 'services', 'staff', 'selectedServiceId'));
    }

    public function checkCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'saloon_id' => 'required|exists:saloons,id',
            'amount' => 'required|numeric'
        ]);

        $coupon = \App\Models\Coupon::where('code', strtoupper($request->code))
            ->where('saloon_id', $request->saloon_id)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired or reached its usage limit.']);
        }

        if (Auth::check()) {
            if (!$coupon->canUserUse(Auth::id())) {
                return response()->json(['success' => false, 'message' => 'You have already used this coupon maximum times.']);
            }
        }

        if ($coupon->min_purchase_amount && $request->amount < $coupon->min_purchase_amount) {
            return response()->json(['success' => false, 'message' => 'Minimum order of ₹' . $coupon->min_purchase_amount . ' required for this coupon.']);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'coupon_id' => $coupon->id,
            'message' => 'Coupon applied successfully!'
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'saloon_id' => 'required|exists:saloons,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'staff_id' => 'nullable|exists:staff,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'coupon_id' => 'nullable|exists:coupons,id',
        ];

        // If guest, require name. Password/Email only if they want an account.
        if (!Auth::check()) {
            $rules['guest_name'] = 'required|string|max:255';
            if ($request->filled('email')) {
                $rules['email'] = 'string|email|max:255|unique:users,email';
                $rules['password'] = 'required|string|min:8';
            }
        }

        $request->validate($rules);

        $saloon = Saloon::findOrFail($request->saloon_id);
        if (!$saloon->isSubscriptionActive()) {
            return back()->with('error', 'This saloon is currently not accepting new bookings.');
        }

        // Handle Registration if requested
        $user = Auth::user();
        if (!Auth::check() && $request->filled('email')) {
            $user = User::create([
                'name' => $request->guest_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);
            Auth::login($user);
        }

        $services = Service::whereIn('id', $request->service_ids)->get();
        $totalMinutes = $services->sum('duration_minutes');
        $totalAmount = $services->sum(function($s) {
            return $s->discounted_price ?? $s->price;
        });

        $discountAmount = 0;
        $couponId = null;

        if ($request->coupon_id) {
            $coupon = \App\Models\Coupon::find($request->coupon_id);
            if ($coupon && $coupon->isValid() && $coupon->saloon_id == $saloon->id) {
                if (!Auth::check() || $coupon->canUserUse(Auth::id())) {
                    $discountAmount = $coupon->calculateDiscount($totalAmount);
                    $couponId = $coupon->id;
                    $coupon->increment('times_used');
                }
            }
        }

        $appointment = new Appointment();
        $appointment->saloon_id = $saloon->id;
        $appointment->user_id = Auth::id() ?: null;
        $appointment->guest_name = Auth::id() ? null : $request->guest_name;
        $appointment->staff_id = $request->staff_id;
        $appointment->service_id = $services->first()->id; // Fallback for single service field
        $appointment->coupon_id = $couponId;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->duration_minutes = $totalMinutes;
        $appointment->total_amount = $totalAmount;
        $appointment->discount_amount = $discountAmount;
        $appointment->final_amount = $totalAmount - $discountAmount;
        $appointment->status = 'pending';
        $appointment->save();

        // Attach Multiple Services
        foreach ($services as $service) {
            $appointment->services()->attach($service->id, [
                'price' => $service->discounted_price ?? $service->price,
                'duration_minutes' => $service->duration_minutes
            ]);
        }

        // Create Payment Record
        $appointment->payment()->create([
            'user_id' => Auth::id(),
            'amount' => $appointment->final_amount,
            'status' => 'pending',
            'payment_method' => 'cash',
        ]);

        $stats = $saloon->getQueueStats();
        $msg = "Success! Your reach time at the saloon is approx: " . $stats['expected_reach_time'];

        if (Auth::check()) {
            return redirect()->route('user.dashboard')->with('success', $msg);
        }

        return redirect()->route('home')->with('success', $msg);
    }
}

