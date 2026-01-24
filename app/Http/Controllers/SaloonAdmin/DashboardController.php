<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $saloon = auth()->user()->saloons()->first();

        if (!$saloon) {
            return redirect()->route('saloon-admin.saloon.create')
                ->with('info', 'Please create your saloon profile first.');
        }

        $isSubscriptionActive = $saloon->isSubscriptionActive();

        $stats = [
            'total_services' => $saloon->services()->count(),
            'active_services' => $saloon->services()->where('is_active', true)->count(),
            'total_staff' => $saloon->staff()->count(),
            'active_staff' => $saloon->staff()->where('is_active', true)->count(),
            'total_appointments' => $saloon->appointments()->count(),
            'today_appointments' => $saloon->appointments()->whereDate('appointment_date', today())->count(),
            'pending_appointments' => $saloon->appointments()->where('status', 'pending')->count(),
            'completed_appointments' => $saloon->appointments()->where('status', 'completed')->count(),
            'total_revenue' => DB::table('payments')
                ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
                ->where('appointments.saloon_id', $saloon->id)
                ->where('payments.status', 'completed')
                ->sum('payments.amount'),
            'monthly_revenue' => DB::table('payments')
                ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
                ->where('appointments.saloon_id', $saloon->id)
                ->where('payments.status', 'completed')
                ->whereMonth('payments.created_at', now()->month)
                ->sum('payments.amount'),
        ];

        $todayAppointments = $saloon->appointments()
            ->with(['user', 'services', 'staff'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $recentAppointments = $saloon->appointments()
            ->with(['user', 'services', 'staff'])
            ->latest()
            ->take(10)
            ->get();

        $topServices = $saloon->services()
            ->withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get();


        return view('saloon-admin.dashboard', compact(
            'saloon',
            'stats',
            'todayAppointments',
            'recentAppointments',
            'topServices',
            'isSubscriptionActive'
        ));
    }
}
