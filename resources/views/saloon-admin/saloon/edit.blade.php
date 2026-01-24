@extends('layouts.dashboard')

@section('title', 'Edit Saloon Profile')
@section('page-title', 'Edit Saloon Profile')
@section('page-subtitle', 'Update your saloon details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('saloon-admin.saloon.update', $saloon->id) }}" method="POST">
            @csrf
            @method('PUT')

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

            <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t">
                <a href="{{ route('saloon-admin.dashboard') }}" class="btn-outline text-gray-600 border-gray-300">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Saloon Profile
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
