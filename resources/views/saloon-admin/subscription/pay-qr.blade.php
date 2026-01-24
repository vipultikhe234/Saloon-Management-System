@extends('layouts.dashboard')

@section('title', 'Complete Payment')
@section('page-title', 'Step 2: Scan & Pay')
@section('page-subtitle', 'Scan the QR code to complete your recharge')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
        <!-- Left Side: Order Details -->
        <div class="p-8 bg-gray-50 border-r border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-shopping-basket text-indigo-600"></i>
                Order Summary
            </h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500">Selected Plan</p>
                        <p class="font-bold text-gray-800">{{ $plan['name'] }}</p>
                    </div>
                    <div class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold uppercase">
                        1 Month
                    </div>
                </div>

                <div class="flex justify-between items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500">Base Price</p>
                        <p class="font-bold text-gray-800">₹{{ $plan['price'] }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-dashed">
                    <div class="flex justify-between items-center px-4 py-2">
                        <span class="text-gray-500 font-medium">GST (0.0%)</span>
                        <span class="text-gray-800">₹0.00</span>
                    </div>
                    <div class="flex justify-between items-center px-4 py-4 rounded-xl text-white mt-2 shadow-lg" style="background: #4f46e5;">
                        <span class="font-bold">Total Amount</span>
                        <span class="text-2xl font-black">₹{{ $plan['price'] }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-sm font-bold text-gray-600 uppercase mb-3">Next Steps:</h4>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="bg-indigo-100 text-indigo-600 w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold leading-none mt-0.5">1</span>
                        <span>Open any UPI app (PhonePe, GPay, Paytm)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-indigo-100 text-indigo-600 w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold leading-none mt-0.5">2</span>
                        <span>Scan the QR code on the right</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-indigo-100 text-indigo-600 w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold leading-none mt-0.5">3</span>
                        <span>Enter any 8-digit testing TID below</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Side: QR Code and Transaction Form -->
        <div class="p-8 flex flex-col items-center justify-center text-center">
            <div class="mb-4 bg-white p-4 rounded-3xl shadow-lg border border-gray-100">
                <img src="{{ $qrUrl }}" alt="UPI Payment QR Code" class="w-64 h-64 mx-auto">
            </div>
            
            <p class="text-gray-500 text-sm mb-6 max-w-xs">
                Scan this QR code to pay via UPI. Since this is a testing environment, you can use any UPI app.
            </p>

            <form action="{{ route('saloon-admin.subscription.verify') }}" method="POST" class="w-full max-w-xs space-y-4">
                @csrf
                <input type="hidden" name="plan" value="{{ $planKey }}">
                
                <div>
                    <label for="transaction_id" class="block text-left text-xs font-bold text-gray-500 uppercase mb-1 ml-1 text-center">Enter Transaction ID (Testing)</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-center font-mono tracking-widest" placeholder="T2024XXXXXXXX" required>
                    @error('transaction_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 text-white rounded-xl font-bold shadow-xl transition-all flex items-center justify-center gap-2 transform hover:-translate-y-1" style="background: #16a34a;">
                    <i class="fas fa-check-double"></i>
                    Verify & Activate
                </button>
                
                <a href="{{ route('saloon-admin.subscription.plans') }}" class="block text-sm text-gray-400 hover:text-gray-600">
                    <i class="fas fa-arrow-left"></i> Change Plan
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
