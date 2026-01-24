<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $plans = [
        'silver' => [
            'name' => 'Silver Plan',
            'price' => 499,
            'duration' => '1 Month',
            'features' => ['Basic Visibility', 'Queue Management', 'Email Support'],
            'gradient' => 'linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%)',
            'accent' => '#7f8c8d'
        ],
        'gold' => [
            'name' => 'Gold Plan',
            'price' => 999,
            'duration' => '1 Month',
            'features' => ['High Visibility', 'Advanced Stats', 'Priority Search', 'Priority Support'],
            'gradient' => 'linear-gradient(135deg, #f1c40f 0%, #f39c12 100%)',
            'accent' => '#d35400'
        ],
        'platinum' => [
            'name' => 'Platinum Plan',
            'price' => 1999,
            'duration' => '1 Month',
            'features' => ['Featured on Home Page', 'Direct Booking Links', 'Custom Analytics', '24/7 VIP Support'],
            'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'accent' => '#4834d4'
        ],
    ];

    public function plans()
    {
        $saloon = auth()->user()->saloons()->firstOrFail();
        $plans = $this->plans;
        return view('saloon-admin.subscription.plans', compact('saloon', 'plans'));
    }

    public function showQR(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:silver,gold,platinum'
        ]);

        $saloon = auth()->user()->saloons()->firstOrFail();
        $planKey = $request->plan;
        $plan = $this->plans[$planKey];

        // Testing QR Code URL (This is a generic sample QR code)
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=upi://pay?pa=saloonpay@upi&pn=SaloonManagement&am=" . $plan['price'] . "&cu=INR";

        return view('saloon-admin.subscription.pay-qr', compact('saloon', 'plan', 'planKey', 'qrUrl'));
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:silver,gold,platinum',
            'transaction_id' => 'required|string'
        ]);

        $saloon = auth()->user()->saloons()->firstOrFail();
        $planKey = $request->plan;
        
        // Update subscription
        $currentExpiry = $saloon->subscription_expires_at ?: now();
        if ($currentExpiry->isPast()) {
            $currentExpiry = now();
        }
        
        $saloon->update([
            'subscription_level' => $planKey,
            'subscription_expires_at' => $currentExpiry->addMonth(),
            'stripe_id' => 'qr_' . $request->transaction_id, // Store transaction ID
        ]);

        return redirect()->route('saloon-admin.dashboard')->with('success', 'Recharge successful! Your ' . ucfirst($planKey) . ' plan is now active for 1 month.');
    }

    public function success()
    {
        return redirect()->route('saloon-admin.dashboard')->with('success', 'Subscription updated successfully.');
    }

    public function cancel()
    {
        return redirect()->route('saloon-admin.dashboard')->with('error', 'Payment was cancelled.');
    }
}
