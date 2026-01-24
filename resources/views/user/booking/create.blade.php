@extends('layouts.public')

@section('title', 'Book Appointment')
@section('page-title', 'Book Appointment')
@section('page-subtitle', 'Schedule your visit to ' . $saloon->name)

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-6 text-white">
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fas fa-calendar-alt"></i> Complete Your Booking
                    </h2>
                    <p class="text-indigo-100 text-sm mt-1">Provide your details and select services below.</p>
                </div>

                <form action="{{ route('user.booking.store') }}" method="POST" class="p-8">
                    @csrf
                    <input type="hidden" name="saloon_id" value="{{ $saloon->id }}">

                    @guest
                        <!-- Guest Info Selection -->
                        <div class="mb-10 p-6 bg-white border-2 border-indigo-50 rounded-2xl shadow-sm">
                            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <i class="fas fa-id-badge text-indigo-600"></i> How would you like to book?
                            </h3>

                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <label class="cursor-pointer">
                                    <input type="radio" name="booking_type" value="quick" class="hidden peer" checked onchange="toggleBookingMode('quick')">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition text-center">
                                        <i class="fas fa-bolt text-xl mb-2 text-gray-400 peer-checked:text-indigo-600"></i>
                                        <p class="font-bold text-sm text-gray-700">Quick Booking</p>
                                        <p class="text-[10px] text-gray-400">Name only</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="booking_type" value="profile" class="hidden peer" onchange="toggleBookingMode('profile')">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition text-center">
                                        <i class="fas fa-user-plus text-xl mb-2 text-gray-400 peer-checked:text-indigo-600"></i>
                                        <p class="font-bold text-sm text-gray-700">Profile Booking</p>
                                        <p class="text-[10px] text-gray-400">Track live updates</p>
                                    </div>
                                </label>
                            </div>

                            <div class="space-y-6">
                                <!-- Common Name Field -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2" for="guest_name">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="guest_name" id="guest_name" class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition" placeholder="e.g. Rahul Sharma" required>
                                </div>

                                <!-- Conditional Profile Fields -->
                                <div id="profile-fields" class="hidden border-t border-indigo-100 pt-6 animate-fadeIn">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2" for="email">Email Address <span class="text-red-500">*</span></label>
                                            <input type="email" name="email" id="email" class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition" placeholder="you@example.com">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2" for="password">Create Password <span class="text-red-500">*</span></label>
                                            <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition" placeholder="Min. 8 chars">
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-3"><i class="fas fa-info-circle mr-1"></i> Selecting this will create an account so you can track all future bookings.</p>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <div class="space-y-10">
                        <!-- Multiple Service Selection -->
                        <section>
                            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <i class="fas fa-magic text-purple-600"></i> Select Services
                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full font-normal">Choose multiple if needed</span>
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($services as $service)
                                    <label class="relative flex items-center p-4 rounded-xl border-2 border-gray-100 hover:border-indigo-200 cursor-pointer transition group has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" 
                                            class="service-checkbox w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition"
                                            data-price="{{ $service->discounted_price ?? $service->price }}"
                                            data-duration="{{ $service->duration_minutes }}"
                                            data-name="{{ $service->name }}"
                                            {{ (isset($selectedServiceId) && $selectedServiceId == $service->id) ? 'checked' : '' }}
                                            onchange="updateTotal()">
                                        
                                        <div class="ml-4 flex-grow">
                                            <p class="font-bold text-gray-800 group-hover:text-indigo-600 transition">{{ $service->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $service->duration_minutes }} mins</p>
                                        </div>
                                        <div class="text-right">
                                            @if($service->discounted_price)
                                                <p class="font-bold text-indigo-600">${{ number_format($service->discounted_price, 2) }}</p>
                                                <p class="text-[10px] text-gray-400 line-through">${{ number_format($service->price, 2) }}</p>
                                            @else
                                                <p class="font-bold text-gray-800">${{ number_format($service->price, 2) }}</p>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </section>

                        <!-- Staff, Date & Time -->
                        <section class="bg-gray-50 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <i class="fas fa-clock text-green-600"></i> Schedule Visit
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Assign Artist</label>
                                    <select name="staff_id" class="w-full px-4 py-3 rounded-xl border-2 border-white focus:border-indigo-500 outline-none transition bg-white shadow-sm">
                                        <option value="">Any Available</option>
                                        @foreach($staff as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                                    <input type="date" name="appointment_date" class="w-full px-4 py-3 rounded-xl border-2 border-white focus:border-indigo-500 outline-none transition bg-white shadow-sm" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Time</label>
                                    <input type="time" name="appointment_time" class="w-full px-4 py-3 rounded-xl border-2 border-white focus:border-indigo-500 outline-none transition bg-white shadow-sm" value="{{ date('H:i') }}" required>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="mt-10 flex gap-4">
                        <button type="submit" class="flex-grow bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-200 transform hover:-translate-y-1">
                            Confirm Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary Stickey Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-8">
                <div class="p-6 border-b border-gray-100">
                    <h4 class="text-lg font-bold text-gray-800">Booking Summary</h4>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Salon Details</p>
                        <p class="font-bold text-indigo-600">{{ $saloon->name }}</p>
                        <p class="text-xs text-gray-500">{{ $saloon->address }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-3">Selected Services</p>
                        <ul id="selected-services-list" class="space-y-3">
                            <li class="text-sm text-gray-400 italic">No services selected</li>
                        </ul>
                    </div>

                    <div class="pt-6 border-t space-y-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-500 font-medium">Subtotal</span>
                            <span id="summary-total-price" class="text-lg font-bold text-gray-800">$0.00</span>
                        </div>
                        
                        <!-- Coupon Input -->
                        <div class="py-3 px-3 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <p class="text-[10px] text-gray-400 uppercase font-black mb-2">Have a Coupon?</p>
                            <div class="flex gap-2">
                                <input type="text" id="coupon-code-input" class="flex-grow px-3 py-2 text-xs font-bold border rounded-lg focus:ring-2 focus:ring-indigo-500 uppercase" placeholder="CODE">
                                <button type="button" onclick="applyCoupon()" class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-indigo-700 transition">APPLY</button>
                            </div>
                            <div id="coupon-message" class="text-[10px] mt-2 hidden"></div>
                            <input type="hidden" name="coupon_id" id="hidden-coupon-id">
                        </div>

                        <div id="discount-row" class="hidden flex justify-between items-center text-green-600 font-bold">
                            <span class="text-sm">Discount</span>
                            <span id="summary-discount-amount">-$0.00</span>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t">
                            <span class="text-gray-800 font-black">Final Total</span>
                            <span id="summary-final-price" class="text-2xl font-black text-indigo-600">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Total Duration</span>
                            <span id="summary-total-duration" class="text-gray-800 font-bold">0 mins</span>
                        </div>
                    </div>

                    @php $stats = $saloon->getQueueStats(); @endphp
                    <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                        <p class="text-[10px] text-indigo-400 uppercase font-bold mb-1">Estimated Reach Time</p>
                        <p class="text-xl font-bold text-indigo-700">{{ $stats['expected_reach_time'] }}</p>
                        <p class="text-[10px] text-indigo-400 mt-2">Reach on time to avoid cancellation.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentDiscount = 0;
    let currentSubtotal = 0;

    function toggleBookingMode(mode) {
        const profileFields = document.getElementById('profile-fields');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        if (mode === 'profile') {
            profileFields.classList.remove('hidden');
            emailInput.setAttribute('required', 'required');
            passwordInput.setAttribute('required', 'required');
        } else {
            profileFields.classList.add('hidden');
            emailInput.removeAttribute('required');
            passwordInput.removeAttribute('required');
        }
    }

    function updateTotal() {
        const checkboxes = document.querySelectorAll('.service-checkbox:checked');
        let total = 0;
        let duration = 0;
        const list = document.getElementById('selected-services-list');
        list.innerHTML = '';

        if (checkboxes.length === 0) {
            list.innerHTML = '<li class="text-sm text-gray-400 italic">No services selected</li>';
        }

        checkboxes.forEach(cb => {
            const price = parseFloat(cb.getAttribute('data-price'));
            const dur = parseInt(cb.getAttribute('data-duration'));
            const name = cb.getAttribute('data-name');
            
            total += price;
            duration += dur;

            const li = document.createElement('li');
            li.className = 'flex justify-between items-center text-sm';
            li.innerHTML = `
                <span class="text-gray-700 font-medium">${name}</span>
                <span class="font-bold text-gray-800">$${price.toFixed(2)}</span>
            `;
            list.appendChild(li);
        });

        currentSubtotal = total;
        document.getElementById('summary-total-price').textContent = '$' + total.toFixed(2);
        document.getElementById('summary-total-duration').textContent = duration + ' mins';
        
        recalculateFinal();
    }

    function recalculateFinal() {
        const finalPrice = Math.max(0, currentSubtotal - currentDiscount);
        document.getElementById('summary-final-price').textContent = '$' + finalPrice.toFixed(2);
    }

    async function applyCoupon() {
        const code = document.getElementById('coupon-code-input').value;
        const messageEl = document.getElementById('coupon-message');
        const discountRow = document.getElementById('discount-row');
        const discountEl = document.getElementById('summary-discount-amount');
        const hiddenId = document.getElementById('hidden-coupon-id');

        if (!code) return;

        try {
            const response = await fetch('{{ route('user.coupon.check') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    saloon_id: '{{ $saloon->id }}',
                    amount: currentSubtotal
                })
            });

            const data = await response.json();

            messageEl.classList.remove('hidden');
            if (data.success) {
                messageEl.className = 'text-[10px] mt-2 text-green-600 font-bold';
                messageEl.textContent = data.message;
                
                currentDiscount = data.discount;
                hiddenId.value = data.coupon_id;
                
                discountRow.classList.remove('hidden');
                discountEl.textContent = '-$' + data.discount.toFixed(2);
                
                recalculateFinal();
            } else {
                messageEl.className = 'text-[10px] mt-2 text-red-500 font-bold';
                messageEl.textContent = data.message;
                
                // Reset discount if failed
                currentDiscount = 0;
                hiddenId.value = '';
                discountRow.classList.add('hidden');
                recalculateFinal();
            }
        } catch (error) {
            console.error('Error applying coupon:', error);
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', updateTotal);
</script>

@endsection
