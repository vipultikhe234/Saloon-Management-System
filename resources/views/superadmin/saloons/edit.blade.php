@extends('layouts.dashboard')

@section('title', 'Edit Saloon')
@section('page-title', 'Edit Saloon')
@section('page-subtitle', 'Update saloon details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('admin.saloons.update', $saloon->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Owner Display -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Saloon Owner</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="font-semibold text-gray-700">Owner: <span class="text-gray-900">{{ $saloon->owner->name }}</span></p>
                    <p class="text-sm text-gray-500">Email: {{ $saloon->owner->email }}</p>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label" for="name">Saloon Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $saloon->name) }}" required>
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="email">Business Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $saloon->email) }}" required>
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label" for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $saloon->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Contact & Address -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Contact & Location</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $saloon->phone) }}" required>
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="zip_code">Zip Code</label>
                        <input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ old('zip_code', $saloon->zip_code) }}" required>
                        @error('zip_code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label" for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="2" required>{{ old('address', $saloon->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="city">City</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $saloon->city) }}" required>
                        @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="state">State</label>
                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $saloon->state) }}" required>
                        @error('state') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Business Hours</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label" for="opening_time">Opening Time</label>
                        <input type="time" name="opening_time" id="opening_time" class="form-control" value="{{ old('opening_time', $saloon->opening_time) }}" required>
                        @error('opening_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="closing_time">Closing Time</label>
                        <input type="time" name="closing_time" id="closing_time" class="form-control" value="{{ old('closing_time', $saloon->closing_time) }}" required>
                        @error('closing_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Subscription Settings -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Subscription Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label" for="subscription_level">Priority Level</label>
                        <select name="subscription_level" id="subscription_level" class="form-control">
                            <option value="silver" {{ old('subscription_level', $saloon->subscription_level) == 'silver' ? 'selected' : '' }}>Silver (Basic)</option>
                            <option value="gold" {{ old('subscription_level', $saloon->subscription_level) == 'gold' ? 'selected' : '' }}>Gold (Standard)</option>
                            <option value="platinum" {{ old('subscription_level', $saloon->subscription_level) == 'platinum' ? 'selected' : '' }}>Platinum (Premium)</option>
                        </select>
                        @error('subscription_level') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="subscription_expires_at">Subscription Expiry</label>
                        <input type="date" name="subscription_expires_at" id="subscription_expires_at" class="form-control" value="{{ old('subscription_expires_at', $saloon->subscription_expires_at ? $saloon->subscription_expires_at->format('Y-m-d') : '') }}">
                        <p class="text-xs text-gray-500 mt-1">Leave blank for indefinite/life-time (if applicable).</p>
                        @error('subscription_expires_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t">
                <a href="{{ route('admin.saloons.index') }}" class="btn-outline text-gray-600 border-gray-300">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Saloon
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('zip_code').addEventListener('input', function() {
        const pin = this.value;
        if (pin.length === 6) {
            const cityInput = document.getElementById('city');
            const stateInput = document.getElementById('state');
            
            // Show loading state
            const originalCityPlaceholder = cityInput.placeholder;
            const originalStatePlaceholder = stateInput.placeholder;
            cityInput.placeholder = 'Loading...';
            stateInput.placeholder = 'Loading...';

            fetch(`https://api.postalpincode.in/pincode/${pin}`)
                .then(response => response.json())
                .then(data => {
                    if (data[0].Status === 'Success') {
                        const postOffice = data[0].PostOffice[0];
                        cityInput.value = postOffice.District;
                        stateInput.value = postOffice.State;
                    } else {
                        cityInput.placeholder = originalCityPlaceholder || 'City';
                        stateInput.placeholder = originalStatePlaceholder || 'State';
                    }
                })
                .catch(err => {
                    console.error('Error fetching pincode:', err);
                    cityInput.placeholder = originalCityPlaceholder || 'City';
                    stateInput.placeholder = originalStatePlaceholder || 'State';
                });
        }
    });
</script>
@endpush
