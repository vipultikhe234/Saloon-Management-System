@extends('layouts.dashboard')

@section('title', $pageTitle)
@section('page-title', $pageTitle)
@section('page-subtitle', 'Manage ' . strtolower($pageTitle))

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">{{ $pageTitle }}</h2>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Add New User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b bg-gray-50">
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Name</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Contact</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Role</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Joined</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="pb-3 px-4 py-3 font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-600">{{ $user->email }}</div>
                        <div class="text-xs text-gray-500">{{ $user->phone }}</div>
                    </td>
                    <td class="py-4 px-4">
                        @if($user->role === 'super_admin')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">Super Admin</span>
                        @elseif($user->role === 'saloon_admin')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Saloon Admin</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Customer</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-gray-600 text-sm">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-4 px-4">
                        @if($user->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-right space-x-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-700" title="Toggle Status">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Delete" onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
