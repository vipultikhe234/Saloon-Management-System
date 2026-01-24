<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_saloons' => Saloon::count(),
            'active_saloons' => Saloon::where('is_active', true)->count(),
            'total_saloon_admins' => User::where('role', 'saloon_admin')->count(),
            'paid_saloons' => Saloon::where('subscription_expires_at', '>', now())->count(),
            'unpaid_saloons' => Saloon::where(function($q) {
                $q->whereNull('subscription_expires_at')
                  ->orWhere('subscription_expires_at', '<=', now());
            })->count(),
        ];

        $recentSaloons = Saloon::with('owner')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'stats',
            'recentSaloons'
        ));
    }
}
