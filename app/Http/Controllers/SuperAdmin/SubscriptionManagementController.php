<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use Illuminate\Http\Request;

class SubscriptionManagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Saloon::with('owner');

        if ($status === 'active') {
            $query->where('subscription_expires_at', '>', now());
        } elseif ($status === 'expired') {
            $query->where(function($q) {
                $q->where('subscription_expires_at', '<=', now())
                  ->orWhereNull('subscription_expires_at');
            });
        }

        $saloons = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Saloon::count(),
            'active' => Saloon::where('subscription_expires_at', '>', now())->count(),
            'expired' => Saloon::where(function($q) {
                $q->where('subscription_expires_at', '<=', now())
                  ->orWhereNull('subscription_expires_at');
            })->count(),
        ];

        return view('superadmin.subscriptions.index', compact('saloons', 'stats', 'status'));
    }

    public function extend(Request $request, Saloon $saloon)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12'
        ]);

        $currentExpiry = $saloon->subscription_expires_at ?: now();
        if ($currentExpiry->isPast()) {
            $currentExpiry = now();
        }

        $saloon->update([
            'subscription_expires_at' => $currentExpiry->addMonths((int) $request->months)
        ]);

        return back()->with('success', "Subscription for {$saloon->name} extended by {$request->months} months.");
    }
}
