@extends('layouts.dashboard')

@section('title', 'Manage Appointments')
@section('page-title', 'Appointments')
@section('page-subtitle', 'Track and manage customer bookings')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <!-- Filters -->
    <form action="{{ route('saloon-admin.appointments.index') }}" method="GET" class="mb-6 pb-6 border-b">
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="form-label text-xs mb-1">Status</label>
                <select name="status" class="form-control py-2 text-sm w-40">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="form-label text-xs mb-1">Date</label>
                <input type="date" name="date" class="form-control py-2 text-sm" value="{{ request('date') }}">
            </div>
            <button type="submit" class="btn-sm bg-gray-900 text-white rounded hover:bg-gray-800 h-10 px-4">
                Filter
            </button>
            @if(request()->hasAny(['status', 'date']))
                <a href="{{ route('saloon-admin.appointments.index') }}" class="text-sm text-gray-500 hover:underline self-center">Clear</a>
            @endif
        </div>
    </form>

    @if($appointments->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b bg-gray-50">
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">ID</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Customer</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Service & Staff</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Date & Time</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($appointments as $apt)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-4 text-xs font-mono text-gray-500">
                        #{{ substr($apt->appointment_number, -6) }}
                    </td>
                    <td class="py-4 px-4">
                        <div class="font-bold text-gray-800">{{ $apt->user ? $apt->user->name : $apt->guest_name }}</div>
                        <div class="text-xs text-gray-500">{{ $apt->user ? $apt->user->phone : 'Guest' }}</div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm font-semibold text-indigo-600">
                            @if($apt->services->count() > 0)
                                {{ $apt->services->pluck('name')->join(', ') }}
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">
                            Staff: {{ $apt->staff ? $apt->staff->name : 'Unassigned' }}
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d') }}</div>
                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</div>
                    </td>
                    <td class="py-4 px-4">
                        @php
                            $colors = [
                                'pending' => 'yellow',
                                'confirmed' => 'blue',
                                'completed' => 'green',
                                'cancelled' => 'red',
                                'no_show' => 'gray'
                            ];
                            $color = $colors[$apt->status] ?? 'gray';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800 uppercase">
                            {{ $apt->status }}
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        @if($apt->status == 'pending')
                            <form action="{{ route('saloon-admin.appointments.update-status', $apt->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn-sm bg-green-600 text-white rounded hover:bg-green-700 mx-1" title="Confirm">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        
                        @if($apt->status == 'confirmed')
                            <form action="{{ route('saloon-admin.appointments.update-status', $apt->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn-sm bg-indigo-600 text-white rounded hover:bg-indigo-700 mx-1" title="Mark Complete">
                                    <i class="fas fa-check-double"></i>
                                </button>
                            </form>
                        @endif

                        @if(!in_array($apt->status, ['completed', 'cancelled']))
                            <form action="{{ route('saloon-admin.appointments.update-status', $apt->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="text-red-500 hover:text-red-700 mx-1 btn-sm border border-red-200 rounded" title="Cancel">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('saloon-admin.appointments.show', $apt->id) }}" class="text-gray-500 hover:text-gray-700 mx-1" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
            <i class="fas fa-calendar-times"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">No Appointments Found</h3>
        <p class="text-gray-500">No appointments match your filters.</p>
    </div>
    @endif
</div>
@endsection
