@extends('layouts.dashboard')

@section('title', 'Manage Saloons')
@section('page-title', 'Values Management')
@section('page-subtitle', 'List and manage all registered saloons')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">All Saloons</h2>
        <a href="{{ route('admin.saloons.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Add New Saloon
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b bg-gray-50">
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">ID</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Saloon Details</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Owner</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-center">Payment Status</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-center">Status</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($saloons as $saloon)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-4 text-gray-800 font-medium">#{{ $saloon->id }}</td>
                    <td class="py-4 px-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 mr-3 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                {{ strtoupper(substr($saloon->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 flex items-center gap-2">
                                    {{ $saloon->name }}
                                    @if($saloon->is_verified)
                                        <i class="fas fa-check-circle text-blue-500 text-xs" title="Verified"></i>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">{{ $saloon->city }}, {{ $saloon->state }}</div>
                                @if(!$saloon->is_verified)
                                    <form action="{{ route('admin.saloons.verify', $saloon->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full hover:bg-yellow-200 transition" onclick="return confirm('Verify this saloon?')">
                                            Click to Verify
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="font-medium text-gray-800">{{ $saloon->owner ? $saloon->owner->name : 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $saloon->owner->phone ?? '' }}</div>
                    </td>
                    <td class="py-4 px-4 text-center">
                        @if($saloon->isSubscriptionActive())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Paid
                            </span>
                            <div class="text-xs text-gray-400 mt-1">Exp: {{ $saloon->subscription_expires_at->format('M d, Y') }}</div>
                        @elseif($saloon->subscription_expires_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-clock mr-1"></i> Expired
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-minus mr-1"></i> Unpaid
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-center">
                        <form action="{{ route('admin.saloons.toggle-status', $saloon->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $saloon->is_active ? 'bg-green-500' : 'bg-gray-300' }}" role="switch" aria-checked="{{ $saloon->is_active ? 'true' : 'false' }}">
                                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $saloon->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                        <div class="text-xs text-gray-400 mt-1">{{ $saloon->is_active ? 'Active' : 'Hidden' }}</div>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('admin.saloons.login-as', $saloon->id) }}" method="POST" target="_blank" class="inline">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center transition" title="Login as Owner">
                                    <i class="fas fa-sign-in-alt text-sm"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.saloons.edit', $saloon->id) }}" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Edit Saloon">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('admin.saloons.destroy', $saloon->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Delete Saloon" onclick="return confirm('Are you sure you want to permanently delete this saloon?')">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $saloons->links() }}
    </div>
</div>
@endsection
