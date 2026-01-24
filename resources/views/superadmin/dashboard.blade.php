@extends('layouts.dashboard')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Super Admin Overview')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-store text-white"></i>
            </div>
            <h3 class="text-gray-500 text-sm font-semibold mb-1">Total Saloons</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_saloons'] }}</p>
            <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check-circle"></i> {{ $stats['active_saloons'] }} Active
            </p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-credit-card text-white"></i>
            </div>
            <h3 class="text-gray-500 text-sm font-semibold mb-1">Paid Saloons</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['paid_saloons'] }}</p>
            <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-check"></i> Currently Active
            </p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);">
                <i class="fas fa-hourglass-half text-white"></i>
            </div>
            <h3 class="text-gray-500 text-sm font-semibold mb-1">Unpaid Saloons</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['unpaid_saloons'] }}</p>
            <p class="text-sm text-red-600 mt-2">
                <i class="fas fa-times-circle"></i> Temporary Off
            </p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                <i class="fas fa-user-tie text-white"></i>
            </div>
            <h3 class="text-gray-500 text-sm font-semibold mb-1">Total Owners</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_saloon_admins'] }}</p>
            <p class="text-sm text-blue-600 mt-2">
                <i class="fas fa-shield-alt"></i> Verified Partners
            </p>
        </div>
    </div>

    <!-- Recent Saloons -->
    <div class="grid grid-cols-1 gap-6">
        <div class="table-container">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-store text-indigo-600 mr-2"></i>Recent Saloons
                </h3>
                <a href="{{ route('admin.saloons.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentSaloons as $saloon)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($saloon->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $saloon->name }}</p>
                                <p class="text-sm text-gray-500">{{ $saloon->city }}, {{ $saloon->state }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600 mb-1">Owner: {{ $saloon->owner->name }}</div>
                            @if($saloon->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">No saloons found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="table-container">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt text-indigo-600 mr-2"></i>Quick Actions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.saloons.create') }}" class="btn-primary text-center">
                <i class="fas fa-plus-circle mr-2"></i>Add Saloon
            </a>
            <a href="{{ route('admin.users.admins') }}" class="btn-primary text-center">
                <i class="fas fa-user-tie mr-2"></i>Manage System Admins
            </a>
        </div>
    </div>
</div>
@endsection
