@extends('layouts.dashboard')

@section('title', 'Subscription Management')
@section('page-title', 'Subscription Management')
@section('page-subtitle', 'Monitor and manage saloon payment statuses')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Saloons</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="p-3 bg-indigo-100 rounded-lg text-indigo-600">
                    <i class="fas fa-store text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Active Paid</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-1">{{ $stats['active'] }}</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-lg text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card border-l-4 border-red-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Expired</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-1">{{ $stats['expired'] }}</h3>
                </div>
                <div class="p-3 bg-red-100 rounded-lg text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex justify-start gap-4 mb-2">
        <a href="{{ route('admin.subscriptions.index', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded-lg font-bold text-sm transition-all {{ $status === 'all' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
            All Saloons
        </a>
        <a href="{{ route('admin.subscriptions.index', ['status' => 'active']) }}" 
           class="px-4 py-2 rounded-lg font-bold text-sm transition-all {{ $status === 'active' ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
            Active Only
        </a>
        <a href="{{ route('admin.subscriptions.index', ['status' => 'expired']) }}" 
           class="px-4 py-2 rounded-lg font-bold text-sm transition-all {{ $status === 'expired' ? 'bg-red-600 text-white shadow-lg' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
            Expired Only
        </a>
    </div>

    <!-- Subscriptions Table -->
    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Saloon Details</th>
                    <th>Plan</th>
                    <th>Expiry Date</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th class="text-right">Quick Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($saloons as $saloon)
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100">
                                    {{ substr($saloon->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $saloon->name }}</p>
                                    <p class="text-xs text-gray-500">Owner: {{ $saloon->owner->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $saloon->subscription_level === 'platinum' ? 'badge-info' : ($saloon->subscription_level === 'gold' ? 'badge-warning' : 'badge-secondary') }} uppercase text-xs">
                                {{ $saloon->subscription_level ?: 'Free' }}
                            </span>
                        </td>
                        <td>
                            @if($saloon->subscription_expires_at)
                                <div class="text-sm font-semibold text-gray-700">
                                    {{ $saloon->subscription_expires_at->format('d M, Y') }}
                                </div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">
                                    {{ $saloon->subscription_expires_at->format('h:i A') }}
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Never</span>
                            @endif
                        </td>
                        <td>
                            @if($saloon->subscription_expires_at)
                                @php
                                    $daysRemaining = now()->diffInDays($saloon->subscription_expires_at, false);
                                @endphp
                                @if($daysRemaining > 0)
                                    <span class="text-green-600 font-bold text-sm">{{ $daysRemaining }} Days</span>
                                @else
                                    <span class="text-red-500 font-bold text-sm">Expired</span>
                                @endif
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td>
                            @if($saloon->isSubscriptionActive())
                                <span class="badge badge-success">ACTIVE</span>
                            @else
                                <span class="badge badge-danger">EXPIRED</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.subscriptions.extend', $saloon) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    <select name="months" class="text-xs border rounded px-1 py-1 focus:ring-1 focus:ring-indigo-300 outline-none">
                                        <option value="1">1 Month</option>
                                        <option value="3">3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="12">1 Year</option>
                                    </select>
                                    <button type="submit" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors" title="Extend Subscription">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.saloons.edit', $saloon) }}" class="p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors" title="Edit Saloon">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-500">
                            <i class="fas fa-search text-4xl mb-3 block opacity-20"></i>
                            No saloons found for the selected status.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-6">
            {{ $saloons->appends(['status' => $status])->links() }}
        </div>
    </div>
</div>
@endsection
