@extends('layouts.dashboard')

@section('title', 'Manage Staff')
@section('page-title', 'Staff Management')
@section('page-subtitle', 'Manage your saloon staff members')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Your Staff</h2>
        <a href="{{ route('saloon-admin.staff.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Styling Expert
        </a>
    </div>

    @if($staff->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b bg-gray-50">
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Expert</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Specialization</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Contact</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($staff as $member)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ $member->name }}</div>
                                <div class="text-xs text-gray-500">{{ $member->experience_years }} Years Exp.</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-gray-600">
                        {{ $member->specialization ?? 'General' }}
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-600">{{ $member->email }}</div>
                        <div class="text-xs text-gray-500">{{ $member->phone }}</div>
                    </td>
                    <td class="py-4 px-4">
                        @if($member->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-right space-x-2">
                        <form action="{{ route('saloon-admin.staff.toggle-status', $member->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                        <a href="{{ route('saloon-admin.staff.edit', $member->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('saloon-admin.staff.destroy', $member->id) }}" method="POST" class="inline">
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
    <div class="mt-4">
        {{ $staff->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
            <i class="fas fa-user-friends"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">No Staff Added</h3>
        <p class="text-gray-500 mb-6">Add your team members to manage appointments efficiently.</p>
        <a href="{{ route('saloon-admin.staff.create') }}" class="btn-primary">
            Add First Staff Member
        </a>
    </div>
    @endif
</div>
@endsection
