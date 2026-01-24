@extends('layouts.dashboard')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')
@section('page-subtitle', '#' . $appointment->appointment_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Booking Information</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Service</p>
                    <p class="font-semibold text-gray-800">{{ $appointment->service->name }}</p>
                    <p class="text-sm text-gray-600">{{ $appointment->service->duration_minutes }} mins</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Status</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold uppercase bg-gray-100 text-gray-800">
                        {{ $appointment->status }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Date</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Time</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Customer Details</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xl">
                    {{ substr($appointment->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $appointment->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $appointment->user->email }}</p>
                    <p class="text-sm text-gray-600">{{ $appointment->user->phone }}</p>
                </div>
            </div>
            @if($appointment->notes)
                <div class="mt-4 bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                    <p class="text-xs text-yellow-600 font-bold mb-1">Customer Notes:</p>
                    <p class="text-sm text-gray-700">{{ $appointment->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions & Payment -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Payment</h3>
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Total Amount</span>
                <span class="font-bold text-xl text-indigo-600">${{ $appointment->final_amount }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500">Method</span>
                <span class="font-medium">Cash (Pay at Venue)</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Manage Status</h3>
            <form action="{{ route('saloon-admin.appointments.update-status', $appointment->id) }}" method="POST" class="space-y-3">
                @csrf
                @if($appointment->status == 'pending')
                    <button name="status" value="confirmed" class="w-full btn-primary bg-green-600 hover:bg-green-700">
                        Confirm Booking
                    </button>
                    <button name="status" value="cancelled" class="w-full btn-outline text-red-600 border-red-200 hover:bg-red-50">
                        Refuse / Cancel
                    </button>
                @elseif($appointment->status == 'confirmed')
                    <button name="status" value="completed" class="w-full btn-primary bg-indigo-600 hover:bg-indigo-700">
                        Mark as Completed
                    </button>
                    <button name="status" value="no_show" class="w-full btn-outline text-gray-600 border-gray-300 hover:bg-gray-50">
                        Mark as No-Show
                    </button>
                @else
                    <div class="text-center text-gray-500 py-2">
                        This appointment is <strong>{{ $appointment->status }}</strong>.
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
