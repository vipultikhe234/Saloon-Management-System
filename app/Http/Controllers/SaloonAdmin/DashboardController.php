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
            'total_services' => Service::where('saloon_id', $saloon->id)->count(),
            'active_services' => Service::where('saloon_id', $saloon->id)
                ->where('is_active', true)->count(),
            'total_staff' => Staff::where('saloon_id', $saloon->id)->count(),
            'active_staff' => Staff::where('saloon_id', $saloon->id)
                ->where('is_active', true)->count(),
            'total_appointments' => Appointment::where('saloon_id', $saloon->id)->count(),
            'today_appointments' => Appointment::where('saloon_id', $saloon->id)
                ->whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('saloon_id', $saloon->id)
                ->where('status', 'pending')->count(),
            'completed_appointments' => Appointment::where('saloon_id', $saloon->id)
                ->where('status', 'completed')->count(),
            'total_revenue' => Payment::whereHas('appointment', function($q) use ($saloon) {
                $q->where('saloon_id', $saloon->id);
            })->where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::whereHas('appointment', function($q) use ($saloon) {
                $q->where('saloon_id', $saloon->id);
            })->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        $todayAppointments = Appointment::with(['user', 'service', 'staff'])
            ->where('saloon_id', $saloon->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $recentAppointments = Appointment::with(['user', 'service', 'staff'])
            ->where('saloon_id', $saloon->id)
            ->latest()
            ->take(10)
            ->get();

        $topServices = Service::where('saloon_id', $saloon->id)
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
