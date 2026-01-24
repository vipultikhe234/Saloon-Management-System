@extends('layouts.dashboard')

@section('title', $saloon->name)
@section('page-title', 'Saloon Details')
@section('page-subtitle', 'Viewing detailed information about ' . $saloon->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Basic Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $saloon->name }}</h2>
                    <p class="text-gray-500">{{ $saloon->address }}, {{ $saloon->city }}, {{ $saloon->state }}</p>
                </div>
                <div class="flex gap-2">
                    @if($saloon->is_verified)
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            <i class="fas fa-check-circle mr-1"></i> Verified
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                            Unverified
                        </span>
                    @endif
                    @if($saloon->is_active)
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm font-semibold text-gray-500">Email</p>
                    <p class="text-gray-800">{{ $saloon->email }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Phone</p>
                    <p class="text-gray-800">{{ $saloon->phone }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Opening Time</p>
                    <p class="text-gray-800">{{ date('h:i A', strtotime($saloon->opening_time)) }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500">Closing Time</p>
                    <p class="text-gray-800">{{ date('h:i A', strtotime($saloon->closing_time)) }}</p>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Description</p>
                <p class="text-gray-700 leading-relaxed">{{ $saloon->description ?? 'No description provided.' }}</p>
            </div>
        </div>

        <!-- Services -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Services ({{ $saloon->services->count() }})</h3>
            @if($saloon->services->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($saloon->services as $service)
                            <tr>
                                <td class="px-4 py-2">{{ $service->name }}</td>
                                <td class="px-4 py-2">${{ number_format($service->price, 2) }}</td>
                                <td class="px-4 py-2">{{ $service->duration_minutes }} mins</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No services listed yet.</p>
            @endif
        </div>
    </div>

    <!-- Right Column: Owner & Stats -->
    <div class="space-y-6">
        <!-- Owner Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Owner Details</h3>
            @if($saloon->owner)
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xl">
                        {{ substr($saloon->owner->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $saloon->owner->name }}</p>
                        <p class="text-sm text-gray-500">Saloon Admin</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <p><i class="fas fa-envelope w-6 text-gray-400"></i> {{ $saloon->owner->email }}</p>
                    <p><i class="fas fa-phone w-6 text-gray-400"></i> {{ $saloon->owner->phone ?? 'N/A' }}</p>
                </div>
            @else
                <p class="text-red-500">No owner assigned.</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Actions</h3>
            <div class="flex flex-col gap-3">
                <a href="{{ route('admin.saloons.edit', $saloon->id) }}" class="btn btn-primary text-center">
                    Edit Saloon Details
                </a>
                
                @if(!$saloon->is_verified)
                    <form action="{{ route('admin.saloons.verify', $saloon->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full btn btn-outline bg-blue-50 text-blue-600 border-blue-200 hover:bg-blue-100 text-center">
                            Mark as Verified
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Stats</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-indigo-600">{{ $saloon->staff->count() }}</p>
                    <p class="text-xs text-gray-500 uppercase">Staff</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ $saloon->rating }}</p>
                    <p class="text-xs text-gray-500 uppercase">Rating</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
