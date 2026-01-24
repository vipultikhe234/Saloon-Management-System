@extends('layouts.public')

@section('title', $saloon->name)
@section('page-title', $saloon->name)
@section('page-subtitle', 'View saloon services and book appointments')

@section('content')
<div class="space-y-6">
    <!-- Hero / Info Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="absolute bottom-0 left-0 p-8 text-white">
                <h1 class="text-3xl font-bold mb-2">{{ $saloon->name }}</h1>
                <p class="flex items-center gap-2 opacity-90">
                    <i class="fas fa-map-marker-alt"></i> {{ $saloon->address }}, {{ $saloon->city }}
                </p>
            </div>
            <div class="absolute bottom-4 right-8 bg-white/90 backdrop-blur px-4 py-2 rounded-lg text-gray-800 font-bold shadow-lg">
                <i class="fas fa-star text-yellow-500 mr-1"></i> {{ $saloon->rating }} <span class="text-gray-500 text-sm font-normal">({{ $saloon->total_reviews }} reviews)</span>
            </div>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <h3 class="font-bold text-gray-800 mb-2">About Us</h3>
                <p class="text-gray-600 leading-relaxed max-w-2xl">{{ $saloon->description ?? 'No description available for this luxury experience.' }}</p>
                
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-2"><i class="fas fa-envelope text-indigo-500"></i> {{ $saloon->email }}</span>
                    <span class="flex items-center gap-2"><i class="fas fa-phone text-indigo-500"></i> {{ $saloon->phone }}</span>
                    <span class="flex items-center gap-2"><i class="fas fa-clock text-indigo-500"></i> {{ date('h:i A', strtotime($saloon->opening_time)) }} - {{ date('h:i A', strtotime($saloon->closing_time)) }}</span>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <h4 class="font-bold text-gray-800 mb-3">Location</h4>
                <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 mb-3">
                    <i class="fas fa-map text-3xl"></i>
                    <!-- Map placeholder -->
                </div>
                <button class="w-full border-2 border-indigo-200 text-indigo-600 py-2 rounded-full hover:bg-indigo-50 font-semibold transition">
                    <i class="fas fa-directions mr-2"></i>Get Directions
                </button>
            </div>
        </div>
    </div>

    <!-- Promotions / Coupons -->
    @if($coupons->count() > 0)
    <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100">
        <h3 class="font-bold text-indigo-900 mb-4 flex items-center gap-2">
            <i class="fas fa-percentage"></i> Available Promotions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($coupons as $coupon)
            <div class="bg-white p-4 rounded-xl shadow-sm border border-indigo-200 flex justify-between items-center group">
                <div>
                    <p class="text-xs font-bold text-indigo-600 uppercase">{{ $coupon->title }}</p>
                    <p class="text-lg font-black text-gray-800">
                        @if($coupon->discount_type == 'percentage')
                            {{ $coupon->discount_value }}% OFF
                        @else
                            ₹{{ $coupon->discount_value }} OFF
                        @endif
                    </p>
                    <p class="text-[10px] text-gray-400">Use code: <span class="text-indigo-600 font-bold select-all">{{ $coupon->code }}</span></p>
                </div>
                <div class="bg-indigo-50 px-3 py-1 rounded-lg border border-dashed border-indigo-300 text-indigo-700 font-mono font-bold text-sm tracking-widest">
                    {{ $coupon->code }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif


    <!-- Services Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 relative">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-cut text-indigo-500"></i> Our Services
            </h2>
            <div id="selection-banner" class="hidden bg-indigo-600 text-white px-6 py-3 rounded-full shadow-lg items-center gap-4 animate-bounce">
                <span class="text-sm font-bold"><span id="selected-count">0</span> Services Selected</span>
                <span class="h-4 w-px bg-white/30"></span>
                <span class="text-lg font-bold" id="floating-total">$0.00</span>
                <button onclick="proceedToBooking()" class="bg-white text-indigo-600 px-4 py-1.5 rounded-full font-bold text-sm hover:bg-yellow-400 hover:text-gray-900 transition">
                    Book Now
                </button>
            </div>
        </div>

        @forelse($services as $categoryName => $categoryServices)
            <div class="mb-10 last:mb-0">
                <h3 class="text-lg font-bold text-gray-700 mb-5 border-l-4 border-indigo-500 pl-3">
                    {{ $categoryName }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categoryServices as $service)
                        <label class="group relative bg-white border-2 border-gray-50 rounded-2xl p-5 hover:border-indigo-200 cursor-pointer transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50">
                            <input type="checkbox" class="service-select hidden" 
                                   value="{{ $service->id }}" 
                                   data-price="{{ $service->discounted_price ?? $service->price }}"
                                   onchange="updateSelection()">
                            
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-bold text-gray-800 transition">{{ $service->name }}</h4>
                                <div class="w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center transition-all group-has-[:checked]:bg-indigo-600 group-has-[:checked]:border-indigo-600">
                                    <i class="fas fa-check text-white text-[10px]"></i>
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 mb-6 line-clamp-2">{{ $service->description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-100 px-2 py-1 rounded">
                                    <i class="fas fa-clock mr-1"></i> {{ $service->duration_minutes }} min
                                </span>
                                <div class="text-right">
                                    @if($service->discounted_price)
                                        <p class="text-lg font-bold text-indigo-600">${{ $service->discounted_price }}</p>
                                        <p class="text-[10px] text-gray-400 line-through">${{ $service->price }}</p>
                                    @else
                                        <p class="text-lg font-bold text-gray-800">${{ $service->price }}</p>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-cut text-4xl mb-3 opacity-20"></i>
                <p>No services currently available.</p>
            </div>
        @endforelse
    </div>

    <script>
        function updateSelection() {
            const selected = document.querySelectorAll('.service-select:checked');
            const banner = document.getElementById('selection-banner');
            const countEl = document.getElementById('selected-count');
            const totalEl = document.getElementById('floating-total');
            
            let total = 0;
            selected.forEach(cb => total += parseFloat(cb.dataset.price));

            if(selected.length > 0) {
                banner.classList.remove('hidden');
                banner.classList.add('flex');
                countEl.textContent = selected.length;
                totalEl.textContent = '$' + total.toFixed(2);
            } else {
                banner.classList.add('hidden');
                banner.classList.remove('flex');
            }
        }

        function proceedToBooking() {
            const selected = Array.from(document.querySelectorAll('.service-select:checked')).map(cb => cb.value);
            const url = new URL('{{ route('user.booking.create', $saloon->id) }}');
            selected.forEach(id => url.searchParams.append('service_ids[]', id));
            window.location.href = url.href;
        }
    </script>

    <!-- Staff Section -->
    @if($staff->count() > 0)
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-users text-indigo-500"></i> Meet Our Experts
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($staff as $member)
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full mb-3 overflow-hidden shadow-inner">
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-2xl font-bold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <p class="font-bold text-gray-800 truncate">{{ $member->name }}</p>
                    <p class="text-xs text-indigo-500 truncate">{{ $member->specialization }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
