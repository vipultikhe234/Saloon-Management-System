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

            <div class="overflow-x-auto">
                <table class="custom-table w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saloon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Verification</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentSaloons as $saloon)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($saloon->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $saloon->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $saloon->city }}, {{ $saloon->state }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $saloon->owner->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $saloon->owner->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($saloon->is_verified)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Verified
                                        </span>
                                    @else
                                        <form action="{{ route('admin.saloons.verify', $saloon) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 cursor-pointer transition-colors" onclick="return confirm('Are you sure you want to verify this saloon?')">
                                                <i class="fas fa-clock mr-1"></i> Pending Approval
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($saloon->isSubscriptionActive())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-credit-card mr-1"></i> Paid
                                        </span>
                                        <div class="text-xs text-gray-400 mt-1">Exp: {{ $saloon->subscription_expires_at->format('M d, Y') }}</div>
                                    @elseif($saloon->subscription_expires_at)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-minus-circle mr-1"></i> Not Subscribed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <form action="{{ route('admin.saloons.toggle-status', $saloon) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $saloon->is_active ? 'bg-green-500' : 'bg-gray-200' }}" role="switch" aria-checked="{{ $saloon->is_active ? 'true' : 'false' }}">
                                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $saloon->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.saloons.show', $saloon) }}" class="text-indigo-600 hover:text-indigo-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.saloons.edit', $saloon) }}" class="text-blue-600 hover:text-blue-900" title="Edit Saloon">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.saloons.login-as', $saloon) }}" method="POST" class="inline-block" target="_blank">
                                            @csrf
                                            <button type="submit" class="text-gray-600 hover:text-gray-900" title="Login as Owner" onclick="return confirm('Login as this saloon admin?')">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No saloons found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
