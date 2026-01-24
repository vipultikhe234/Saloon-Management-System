@extends('layouts.dashboard')

@section('title', 'Saloon Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Saloon Management Overview')

@section('content')
<div class="space-y-6">
    @if($saloon)
        <!-- Saloon Info Banner -->
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="flex flex-col md:flex-row items-center justify-between text-white gap-4">
                <div>
                    <h2 class="text-2xl font-bold mb-2">{{ $saloon->name }}</h2>
                    <p class="text-indigo-100"><i class="fas fa-map-marker-alt mr-2"></i>{{ $saloon->address }}, {{ $saloon->city }}</p>
                    <p class="text-indigo-100 mt-1"><i class="fas fa-phone mr-2"></i>{{ $saloon->phone }}</p>
                    
                    <div class="mt-4 inline-flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-crown text-yellow-300 mr-2"></i>
                        <span class="font-semibold uppercase tracking-wider text-sm">
                            {{ $saloon->subscription_level ?: 'Free' }} Plan
                        </span>
                        @if($saloon->subscription_expires_at)
                            <span class="ml-2 text-xs text-indigo-200">
                                (Expires: {{ $saloon->subscription_expires_at->format('M d, Y') }})
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col items-end text-right gap-3">
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="text-3xl font-bold">{{ number_format($saloon->rating, 1) }}</div>
                            <div class="text-indigo-100 text-xs">
                                <i class="fas fa-star text-yellow-300"></i> Rating
                            </div>
                        </div>
                        <div class="h-12 w-px bg-white bg-opacity-20"></div>
                        <div class="text-right">
                            @if($saloon->isSubscriptionActive())
                                <div class="px-3 py-1 bg-green-500 rounded-full text-xs font-bold uppercase tracking-tight">Active</div>
                            @else
                                <div class="px-3 py-1 bg-red-500 rounded-full text-xs font-bold uppercase tracking-tight">
                                    {{ !$saloon->subscription_expires_at ? 'Inactive' : 'Expired' }}
                                </div>
                            @endif
                            <div class="text-indigo-100 text-xs mt-1">Status</div>
                        </div>
                    </div>

                    <a href="{{ route('saloon-admin.subscription.plans') }}" class="px-6 py-2 bg-yellow-400 hover:bg-yellow-500 text-indigo-900 font-bold rounded-lg transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-bolt"></i>
                        {{ $saloon->isSubscriptionActive() ? 'Extend Subscription' : 'Recharge Now' }}
                    </a>
                </div>
            </div>
        </div>

        @if(!$saloon->isSubscriptionActive())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            @if(!$saloon->subscription_expires_at)
                                <span class="font-bold">Welcome!</span> To start using the application and managing your saloon, you need to subscribe to a plan. <span class="font-bold">Please make a payment to activate your account.</span>
                            @else
                                <span class="font-bold">Important:</span> Your subscription has expired. Your saloon is currently <span class="font-bold">Hidden</span> from customers. Please recharge to resume services.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-concierge-bell text-white"></i>
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-1">Services</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_services'] }}</p>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-check-circle"></i> {{ $stats['active_services'] }} Active
                </p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-user-tie text-white"></i>
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-1">Staff Members</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_staff'] }}</p>
                <p class="text-sm text-blue-600 mt-2">
                    <i class="fas fa-check"></i> {{ $stats['active_staff'] }} Active
                </p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-calendar-day text-white"></i>
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-1">Today's Appointments</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['today_appointments'] }}</p>
                <p class="text-sm text-orange-600 mt-2">
                    <i class="fas fa-clock"></i> {{ $stats['pending_appointments'] }} Pending
                </p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="fas fa-rupee-sign text-white"></i>
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-1">Monthly Revenue</h3>
                <p class="text-3xl font-bold text-gray-800">₹{{ number_format($stats['monthly_revenue'], 0) }}</p>
                <p class="text-sm text-gray-500 mt-2">
                    Total: ₹{{ number_format($stats['total_revenue'], 0) }}
                </p>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="table-container">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-calendar-day text-indigo-600 mr-2"></i>Today's Appointments
                </h3>
                <a href="{{ route('saloon-admin.appointments.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if($todayAppointments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Staff</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>
                                        <span class="font-semibold">{{ date('h:i A', strtotime($appointment->appointment_time)) }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="font-semibold">{{ $appointment->user ? $appointment->user->name : $appointment->guest_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $appointment->user ? $appointment->user->phone : 'Guest' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        @if($appointment->services->count() > 0)
                                            {{ $appointment->services->pluck('name')->join(', ') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $appointment->staff->name ?? 'Not Assigned' }}</td>
                                    <td class="font-semibold">₹{{ number_format($appointment->final_amount, 2) }}</td>
                                    <td>
                                        @if($appointment->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="badge badge-info">Confirmed</span>
                                        @elseif($appointment->status === 'in_progress')
                                            <span class="badge badge-primary">In Progress</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @else
                                            <span class="badge badge-danger">{{ ucfirst($appointment->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('saloon-admin.appointments.show', $appointment) }}" class="text-indigo-600 hover:text-indigo-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 py-8">No appointments scheduled for today</p>
            @endif
        </div>

        <!-- Top Services and Recent Appointments -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Services -->
            <div class="table-container">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-fire text-orange-600 mr-2"></i>Top Services
                </h3>
                <div class="space-y-3">
                    @forelse($topServices as $service)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                    <i class="fas fa-cut"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $service->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $service->appointments_count }} bookings</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">₹{{ number_format($service->price, 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">No services found</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="table-container">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-history text-blue-600 mr-2"></i>Recent Appointments
                </h3>
                <div class="space-y-3">
                    @forelse($recentAppointments->take(5) as $appointment)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-all">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $appointment->user ? $appointment->user->name : $appointment->guest_name }}</p>
                                <p class="text-sm text-gray-500">
                                    @if($appointment->services->count() > 0)
                                        {{ $appointment->services->pluck('name')->join(', ') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $appointment->appointment_date->format('M d, Y') }} • 
                                    {{ date('h:i A', strtotime($appointment->appointment_time)) }}
                                </p>
                            </div>
                            <div>
                                @if($appointment->status === 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif($appointment->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($appointment->status) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">No appointments found</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="table-container">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt text-indigo-600 mr-2"></i>Quick Actions
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('saloon-admin.services.create') }}" class="btn-primary text-center">
                    <i class="fas fa-plus-circle mr-2"></i>Add Service
                </a>
                <a href="{{ route('saloon-admin.staff.create') }}" class="btn-primary text-center">
                    <i class="fas fa-user-plus mr-2"></i>Add Staff
                </a>
                <a href="{{ route('saloon-admin.appointments.index') }}" class="btn-primary text-center">
                    <i class="fas fa-calendar-check mr-2"></i>View Appointments
                </a>
                <a href="{{ route('saloon-admin.services.index') }}" class="btn-primary text-center">
                    <i class="fas fa-list mr-2"></i>Manage Services
                </a>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
    }
</style>
@endpush
@endsection
