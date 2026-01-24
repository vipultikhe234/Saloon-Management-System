@extends('layouts.dashboard')

@section('title', 'Add Staff')
@section('page-title', 'Add New Staff')
@section('page-subtitle', 'Add a new member to your team')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('saloon-admin.staff.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Personal Info -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label" for="gender">Gender</label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label" for="experience_years">Experience (Years)</label>
                        <input type="number" name="experience_years" id="experience_years" class="form-control" value="{{ old('experience_years', 0) }}" min="0" required>
                    </div>
                </div>

                <!-- Professional Info -->
                <div>
                    <label class="form-label" for="specialization">Specialization <span class="text-gray-400 font-normal">(e.g. Haircut, Spa)</span></label>
                    <input type="text" name="specialization" id="specialization" class="form-control" value="{{ old('specialization') }}">
                </div>

                <div>
                    <label class="form-label" for="photo">Profile Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Recommended: Square image, 500x500px.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t">
                <a href="{{ route('saloon-admin.staff.index') }}" class="btn-outline text-gray-600 border-gray-300">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Save Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
