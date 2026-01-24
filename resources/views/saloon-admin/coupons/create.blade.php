@extends('layouts.dashboard')

@section('title', 'Create Coupon')
@section('page-title', 'New Coupon')
@section('page-subtitle', 'Define your discount offer details')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('saloon-admin.coupons.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Code & Title -->
                <div class="space-y-4">
                    <div>
                        <label class="form-label font-bold text-gray-700">Coupon Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="form-control" placeholder="e.g., SUMMER50" required style="text-transform: uppercase;">
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label font-bold text-gray-700">Title / Display Name</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g., Summer Special Discount" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Discount Type & Value -->
                <div class="space-y-4">
                    <div>
                        <label class="form-label font-bold text-gray-700">Discount Type</label>
                        <select name="discount_type" class="form-control" required>
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label font-bold text-gray-700">Discount Value</label>
                        <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}" class="form-control" placeholder="e.g., 20" required>
                    </div>
                </div>

                <!-- Usage Limits -->
                <div class="space-y-4">
                    <div>
                        <label class="form-label font-bold text-gray-700">How many times each user can use it?</label>
                        <input type="number" name="usage_per_user" value="{{ old('usage_per_user', 1) }}" class="form-control" placeholder="Blank for unlimited">
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Limit usage per customer</p>
                    </div>
                    <div>
                        <label class="form-label font-bold text-gray-700">Total Usage Limit</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="form-control" placeholder="Blank for unlimited">
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Total redemptions for this coupon</p>
                    </div>
                </div>

                <!-- Minimum Order -->
                <div class="space-y-4">
                    <div>
                        <label class="form-label font-bold text-gray-700">Minimum Purchase Amount (₹)</label>
                        <input type="number" step="0.01" name="min_purchase_amount" value="{{ old('min_purchase_amount', 0) }}" class="form-control">
                    </div>
                </div>

                <!-- Dates -->
                <div class="space-y-4">
                    <div>
                        <label class="form-label font-bold text-gray-700">Valid From</label>
                        <input type="date" name="valid_from" value="{{ old('valid_from', date('Y-m-d')) }}" class="form-control" required>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="form-label font-bold text-gray-700">Valid Until</label>
                        <input type="date" name="valid_until" value="{{ old('valid_until', date('Y-m-d', strtotime('+1 month'))) }}" class="form-control" required>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label font-bold text-gray-700">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t flex justify-end gap-4">
                <a href="{{ route('saloon-admin.coupons.index') }}" class="btn bg-gray-100 text-gray-600 hover:bg-gray-200 px-6 py-2 rounded-xl font-bold">Cancel</a>
                <button type="submit" class="btn bg-indigo-600 text-white hover:bg-indigo-700 px-8 py-2 rounded-xl font-black shadow-lg shadow-indigo-600/20">
                    Create Coupon
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
