@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your account information and password')

@section('content')
@php
    $prefix = auth()->user()->isSuperAdmin() ? 'admin.' : (auth()->user()->isSaloonAdmin() ? 'saloon-admin.' : 'user.');
    $updateRoute = route($prefix . 'profile.update');
@endphp

<div class="max-w-4xl mx-auto space-y-6">
    
    @if (session('status') === 'profile-updated')
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative" role="alert">
            <p class="font-bold">Success!</p>
            <p>Your profile information has been saved.</p>
        </div>
    @endif

    <!-- Profile Information -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Profile Information</h3>
        <p class="text-sm text-gray-500 mb-6">Update your account's profile information and email address.</p>

        <form method="post" action="{{ $updateRoute }}" class="space-y-6">
            @csrf
            @method('patch')

            <div>
                <label class="form-label" for="name">Name</label>
                <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label" for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Update Password</h3>
        <p class="text-sm text-gray-500 mb-6">Ensure your account is using a long, random password to stay secure.</p>

        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div>
                <label class="form-label" for="current_password">Current Password</label>
                <input class="form-control" id="current_password" name="current_password" type="password" autocomplete="current-password" />
                @error('current_password', 'updatePassword') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label" for="password">New Password</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="new-password" />
                @error('password', 'updatePassword') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                @error('password_confirmation', 'updatePassword') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary">Update Password</button>
                @if (session('status') === 'password-updated')
                    <p class="text-sm text-green-600 font-semibold fade-out">Saved.</p>
                @endif
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-red-700 mb-1">Delete Account</h3>
        <p class="text-sm text-red-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

        <button class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition" onclick="document.getElementById('delete-account-modal').showModal()">
            Delete Account
        </button>

        <dialog id="delete-account-modal" class="modal rounded-xl shadow-2xl p-0 backdrop:bg-gray-900/50">
            <div class="p-6 w-full max-w-lg bg-white">
                <h3 class="text-lg font-bold text-gray-900">Are you sure you want to delete your account?</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <form method="post" action="{{ route($prefix . 'profile.destroy') }}" class="mt-6">
                    @csrf
                    @method('delete')

                    <div class="mt-4">
                        <label for="password_delete" class="sr-only">Password</label>
                        <input id="password_delete" name="password" type="password" class="form-control" placeholder="Password" />
                        @error('password', 'userDeletion') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" class="btn-outline text-gray-600 border-gray-300" onclick="document.getElementById('delete-account-modal').close()">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700">Delete Account</button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</div>
@endsection
