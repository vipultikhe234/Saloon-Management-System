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
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Name</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Owner</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Location</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Verified</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($saloons as $saloon)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-4 text-gray-800">#{{ $saloon->id }}</td>
                    <td class="py-4 px-4">
                        <div class="font-semibold text-gray-800">{{ $saloon->name }}</div>
                        <div class="text-sm text-gray-500">{{ $saloon->email }}</div>
                    </td>
                    <td class="py-4 px-4 text-gray-600">{{ $saloon->owner ? $saloon->owner->name : 'N/A' }}</td>
                    <td class="py-4 px-4 text-gray-600">{{ $saloon->city }}, {{ $saloon->state }}</td>
                    <td class="py-4 px-4">
                        @if($saloon->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="py-4 px-4">
                        @if($saloon->is_verified)
                            <span class="text-blue-600"><i class="fas fa-check-circle"></i> Verified</span>
                        @else
                            <form action="{{ route('admin.saloons.verify', $saloon->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-green-600 text-sm underline" onclick="return confirm('Verify this saloon?')">
                                    Pending verification
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-right space-x-2">
                        <form action="{{ route('admin.saloons.login-as', $saloon->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-indigo-500 hover:text-indigo-700" title="Login as Owner">
                                <i class="fas fa-sign-in-alt"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.saloons.show', $saloon->id) }}" class="text-blue-500 hover:text-blue-700" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.saloons.edit', $saloon->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.saloons.toggle-status', $saloon->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.saloons.destroy', $saloon->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Delete" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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
