@extends('layouts.dashboard')

@section('title', $saloon->name . ' - Temporarily Closed')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-2xl w-full text-center px-4">
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-100 text-red-500 mb-6 animate-pulse">
                <i class="fas fa-store-slash text-4xl"></i>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $saloon->name }}</h1>
            <div class="inline-block px-4 py-1.5 bg-red-500 text-white text-sm font-bold rounded-full uppercase tracking-widest mb-6">
                Temporarily Closed
            </div>
            <p class="text-xl text-gray-600 leading-relaxed">
                We're sorry! This saloon is currently not accepting appointments at the moment. 
                Please check back later or explore other amazing saloons in your area.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
            <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="text-indigo-500 text-2xl mb-3">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Find Alternatives</h3>
                <p class="text-sm text-gray-500 mb-4">Discover other top-rated saloons nearby with similar services.</p>
                <a href="{{ route('saloons') }}" class="text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                    Browse Saloons <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="text-purple-500 text-2xl mb-3">
                    <i class="fas fa-home"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Back to Home</h3>
                <p class="text-sm text-gray-500 mb-4">Go back to the main page to see featured services and categories.</p>
                <a href="{{ route('home') }}" class="text-purple-600 font-semibold hover:text-purple-800 transition-colors">
                    Go Home <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>

        <p class="text-gray-400 text-sm italic">
            Are you the owner? <a href="{{ route('login') }}" class="text-gray-600 underline font-medium">Login to your dashboard</a> to manage your subscription.
        </p>
    </div>
</div>
@endsection
