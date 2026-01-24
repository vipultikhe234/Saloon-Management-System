@extends('layouts.dashboard')

@section('title', 'Manage Services')
@section('page-title', 'My Services')
@section('page-subtitle', 'Manage services offered at your saloon')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">All Services</h2>
        <a href="{{ route('saloon-admin.services.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Add New Service
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b bg-gray-50">
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Service Name</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Category</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Price</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Duration</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Gender</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($services as $service)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-4 font-semibold text-gray-800">{{ $service->name }}</td>
                    <td class="py-4 px-4 text-gray-600">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-sm">
                            {!! $service->category->icon !!} {{ $service->category->name }}
                        </span>
                    </td>
                    <td class="py-4 px-4 text-gray-800 font-medium">
                        @if($service->discounted_price)
                            <span class="text-green-600">${{ number_format($service->discounted_price, 2) }}</span>
                            <span class="text-xs text-gray-400 line-through">${{ number_format($service->price, 2) }}</span>
                        @else
                            ${{ number_format($service->price, 2) }}
                        @endif
                    </td>
                    <td class="py-4 px-4 text-gray-600">{{ $service->duration_minutes }} mins</td>
                    <td class="py-4 px-4">
                        @if($service->gender == 'male')
                            <span class="text-blue-500"><i class="fas fa-mars"></i> Male</span>
                        @elseif($service->gender == 'female')
                            <span class="text-pink-500"><i class="fas fa-venus"></i> Female</span>
                        @else
                            <span class="text-purple-500"><i class="fas fa-venus-mars"></i> Unisex</span>
                        @endif
                    </td>
                    <td class="py-4 px-4">
                        @if($service->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-right space-x-2">
                        <a href="{{ route('saloon-admin.services.edit', $service->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('saloon-admin.services.toggle-status', $service->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                        <form action="{{ route('saloon-admin.services.destroy', $service->id) }}" method="POST" class="inline">
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
        {{ $services->links() }}
    </div>
</div>
@endsection
