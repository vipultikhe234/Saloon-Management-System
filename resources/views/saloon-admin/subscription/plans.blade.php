@extends('layouts.dashboard')

@section('title', 'Select Subscription Plan')
@section('page-title', 'Subscription Plans')
@section('page-subtitle', 'Choose a plan that fits your business needs')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
@foreach($plans as $key => $plan)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all hover:scale-105 border-2 {{ $saloon->subscription_level == $key ? 'border-yellow-400' : 'border-transparent' }} flex flex-col h-full">
                <div style="background: {{ $plan['gradient'] }}" class="p-8 text-center text-white">
                    @if($saloon->subscription_level == $key)
                        <div class="inline-block px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs font-bold uppercase mb-2">Current Plan</div>
                    @endif
                    <h3 class="text-2xl font-bold uppercase tracking-wider">{{ $plan['name'] }}</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-extrabold">₹{{ $plan['price'] }}</span>
                        <span class="text-sm opacity-80">/ month</span>
                    </div>
                </div>
                
                <div class="p-8 flex-grow flex flex-col">
                    <ul class="space-y-4 mb-8 flex-grow">
                        @foreach($plan['features'] as $feature)
                            <li class="flex items-center text-gray-600 font-medium">
                                <i class="fas fa-check-circle mr-3" style="color: {{ $plan['accent'] }}"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <form action="{{ route('saloon-admin.subscription.recharge') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $key }}">
                        <button type="submit" 
                                style="background: {{ $plan['gradient'] }}" 
                                class="w-full py-4 rounded-xl font-bold text-lg transition-all shadow-lg text-white hover:opacity-90 transform hover:-translate-y-1">
                            Select {{ $key == 'platinum' ? 'Premium' : ucfirst($key) }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12 text-center bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
        <p class="text-indigo-900 flex items-center justify-center gap-2">
            <i class="fas fa-shield-alt"></i>
            Secure UPI Payment via QR Code. Instant activation.
        </p>
    </div>
</div>
@endsection
