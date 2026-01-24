@extends('layouts.dashboard')

@section('title', 'Manage Coupons')
@section('page-title', 'Coupons')
@section('page-subtitle', 'Create and manage your business discount codes')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('saloon-admin.coupons.create') }}" class="btn bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-2 rounded-xl font-bold flex items-center gap-2">
            <i class="fas fa-plus"></i> Create New Coupon
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b bg-gray-50">
                        <th class="px-6 py-4 font-semibold text-gray-600">Code</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Title</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Discount</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Usage (User/Total)</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Validity</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 font-mono font-bold rounded-lg uppercase tracking-wider">
                                {{ $coupon->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $coupon->title }}</div>
                            @if($coupon->min_purchase_amount)
                            <div class="text-[10px] text-gray-400 uppercase tracking-tighter">Min: ₹{{ number_format($coupon->min_purchase_amount, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-green-600 font-black">
                                @if($coupon->discount_type == 'percentage')
                                    {{ $coupon->discount_value }}% OFF
                                @else
                                    ₹{{ $coupon->discount_value }} OFF
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-600">
                            {{ $coupon->usage_per_user ?? '∞' }} / {{ $coupon->usage_limit ?? '∞' }}
                            <div class="text-xs text-gray-400 italic">Used {{ $coupon->times_used }} times</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-600">Until {{ $coupon->valid_until->format('d M, Y') }}</div>
                            @if($coupon->valid_until->isPast())
                                <span class="text-[10px] text-red-500 font-bold uppercase">Expired</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $coupon->is_active ? 'ACTIVE' : 'INACTIVE' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('saloon-admin.coupons.toggle', $coupon->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 {{ $coupon->is_active ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded-lg hover:opacity-80" title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $coupon->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('saloon-admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete this coupon?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-gray-50 text-gray-400 rounded-lg hover:text-red-600" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="mb-3 text-4xl opacity-20"><i class="fas fa-percentage"></i></div>
                            <p>No coupons found. Create your first discount code!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
