<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use App\Models\Service;
use App\Models\Category;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Review;
use App\Models\Coupon;

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

        // Eager load owner to prevent N+1 queries in view
        $featuredSaloons = $query->with('owner')
            ->orderByRaw("FIELD(subscription_level, 'platinum', 'gold', 'silver', 'free')")
            ->orderByDesc('rating')
            ->take(8)
            ->get();

        // Cache categories for 1 hour
        $categories = cache()->remember('categories_active', 3600, function() {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        // Eager load relationships
        $featuredServices = Service::where('is_active', true)
            ->where('is_featured', true)
            ->with(['saloon', 'category'])
            ->take(12)
            ->get();

        // Cache stats for 30 minutes
        $stats = cache()->remember('home_stats', 1800, function() {
            return [
                'total_saloons' => Saloon::where('is_active', true)->count(),
                'total_services' => Service::where('is_active', true)->count(),
                'happy_customers' => Appointment::where('status', 'completed')->distinct('user_id')->count('user_id'),
            ];
        });

        // Cache location lists for 2 hours
        $states = cache()->remember('active_states', 7200, function() {
            return Saloon::where('is_active', true)->distinct()->pluck('state')->sort();
        });
        
        $cities = cache()->remember('active_cities', 7200, function() {
            return Saloon::where('is_active', true)->distinct()->pluck('city')->sort();
        });

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

        // Use simplePaginate for even faster loading if needed, or paginate with count
        $saloons = $query->withCount('services')
            ->with('owner')
            ->orderByRaw("FIELD(subscription_level, 'platinum', 'gold', 'silver', 'free')")
            ->orderByDesc('rating')
            ->paginate(12);

        // Reuse cached locations
        $states = cache()->remember('active_states', 7200, function() {
            return Saloon::where('is_active', true)->distinct()->pluck('state')->sort();
        });
        
        $cities = cache()->remember('active_cities', 7200, function() {
            return Saloon::where('is_active', true)->distinct()->pluck('city')->sort();
        });

        return view('user.saloons', compact('saloons', 'states', 'cities'));
    }

    public function saloonDetail($slug)
    {
        // Eager load everything needed for the detail page
        $saloon = Saloon::with(['services.category', 'staff', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (!$saloon->isSubscriptionActive() && $saloon->subscription_expires_at !== null) {
            return view('user.saloon-closed', compact('saloon'));
        }

        // Services are already loaded via with()
        $services = $saloon->services->where('is_active', true)->groupBy('category.name');

        $staff = $saloon->staff->where('is_active', true);

        $reviews = $saloon->reviews->where('is_approved', true)->take(10);

        // Fetch active coupons for this saloon
        $coupons = Coupon::where('saloon_id', $saloon->id)
            ->where('is_active', true)
            ->where('valid_until', '>=', now())
            ->where('valid_from', '<=', now())
            ->get();

        return view('user.saloon-detail', compact('saloon', 'services', 'staff', 'reviews', 'coupons'));
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
