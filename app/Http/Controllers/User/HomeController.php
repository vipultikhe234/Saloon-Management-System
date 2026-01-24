<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use App\Models\Service;
use App\Models\Category;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Saloon::where('is_active', true)
            ->where('is_verified', true)
            ->where('subscription_expires_at', '>', now());

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $featuredSaloons = $query->orderByRaw("FIELD(subscription_level, 'platinum', 'gold', 'silver', 'free')")
            ->orderByDesc('rating')
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $featuredServices = Service::where('is_active', true)
            ->where('is_featured', true)
            ->with(['saloon', 'category'])
            ->take(12)
            ->get();

        $stats = [
            'total_saloons' => Saloon::where('is_active', true)->count(),
            'total_services' => Service::where('is_active', true)->count(),
            'happy_customers' => Appointment::where('status', 'completed')->distinct('user_id')->count('user_id'),
        ];

        $states = Saloon::where('is_active', true)->distinct()->pluck('state')->sort();
        $cities = Saloon::where('is_active', true)->distinct()->pluck('city')->sort();

        return view('user.home', compact(
            'featuredSaloons',
            'categories',
            'featuredServices',
            'stats',
            'states',
            'cities'
        ));
    }

    public function saloons(Request $request)
    {
        $query = Saloon::where('is_active', true)
            ->where('is_verified', true)
            ->where('subscription_expires_at', '>', now());

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%')
                    ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $saloons = $query->withCount('services')
            ->orderByRaw("FIELD(subscription_level, 'platinum', 'gold', 'silver', 'free')")
            ->orderByDesc('rating')
            ->paginate(12);

        $states = Saloon::where('is_active', true)->distinct()->pluck('state')->sort();
        $cities = Saloon::where('is_active', true)->distinct()->pluck('city')->sort();

        return view('user.saloons', compact('saloons', 'states', 'cities'));
    }

    public function saloonDetail($slug)
    {
        $saloon = Saloon::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (!$saloon->isSubscriptionActive() && $saloon->subscription_expires_at !== null) {
            return view('user.saloon-closed', compact('saloon'));
        }

        $services = Service::where('saloon_id', $saloon->id)
            ->where('is_active', true)
            ->with('category')
            ->get()
            ->groupBy('category.name');

        $staff = Staff::where('saloon_id', $saloon->id)
            ->where('is_active', true)
            ->get();

        $reviews = Review::where('saloon_id', $saloon->id)
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('user.saloon-detail', compact('saloon', 'services', 'staff', 'reviews'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        $upcomingAppointments = Appointment::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('appointment_date', '>=', today())
            ->with(['saloon', 'service', 'staff'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $pastAppointments = Appointment::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['saloon', 'service', 'staff'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_bookings' => Appointment::where('user_id', $user->id)->count(),
            'completed_bookings' => Appointment::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'upcoming_bookings' => $upcomingAppointments->count(),
        ];

        return view('user.dashboard', compact('upcomingAppointments', 'pastAppointments', 'stats'));
    }
}
