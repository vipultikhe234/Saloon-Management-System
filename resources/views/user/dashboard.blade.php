@extends('layouts.dashboard')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')
@section('page-subtitle', 'Manage your appointments and account')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-semibold uppercase">Total Bookings</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_bookings'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-semibold uppercase">Upcoming</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['upcoming_bookings'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-semibold uppercase">Completed</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['completed_bookings'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-500">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="bg-white rounded-xl shadow-lg mb-8">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">
            <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>Upcoming Appointments
        </h3>
        <a href="{{ route('saloons') }}" class="btn-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Book New
        </a>
    </div>

    @if($upcomingAppointments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saloon</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($upcomingAppointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $appointment->saloon->name }}</div>
                            <div class="text-xs text-gray-500">{{ $appointment->saloon->city }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                @if($appointment->services->count() > 0)
                                    {{ $appointment->services->pluck('name')->implode(', ') }}
                                @else
                                    {{ $appointment->service->name ?? 'Multiple Services' }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${{ number_format($appointment->final_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($appointment->status == 'confirmed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('user.appointment.cancel', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-8 text-center text-gray-500">
            <p>You have no upcoming appointments.</p>
        </div>
    @endif
</div>

<!-- Past History -->
<div class="bg-white rounded-xl shadow-lg">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold text-gray-800">
            <i class="fas fa-history text-gray-400 mr-2"></i>Booking History
        </h3>
    </div>
    
    @if($pastAppointments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saloon</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pastAppointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $appointment->saloon->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($appointment->services->count() > 0)
                                {{ $appointment->services->pluck('name')->implode(', ') }}
                            @else
                                {{ $appointment->service->name ?? 'Multiple Services' }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($appointment->status == 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-6 text-center text-gray-500 text-sm">
            <p>No past booking history found.</p>
        </div>
    @endif
</div>
@endsection
