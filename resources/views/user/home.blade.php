@extends('layouts.public')

@section('title', 'Saloon Management System - Premium Beauty & Grooming')

@push('styles')
<style>
    /* Premium Animations */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    @keyframes float-delayed {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }

    /* Hero Background */
    .hero-premium {
        background: radial-gradient(circle at top right, #4338ca 0%, #312e81 40%, #1e1b4b 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-premium::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* Glassmorphism */
    .glass-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .glass-card-dark {
        background: rgba(30, 27, 75, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Custom Form Elements */
    .custom-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        appearance: none;
    }

    /* Text Gradients */
    .text-gradient {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .text-gradient-purple {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Floaters */
    .float-item { animation: float 6s ease-in-out infinite; }
    .float-item-delay { animation: float-delayed 8s ease-in-out infinite; }
</style>
@endpush

@section('content')
<!-- Premium Hero Section -->
<section class="hero-premium min-h-[90vh] flex items-center relative pt-20">
    <!-- Decorative Glows -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/30 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10 grid lg:grid-cols-2 gap-16 items-center">
        <!-- Text Content -->
        <div class="text-center lg:text-left pt-10 lg:pt-0">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card-dark text-amber-400 text-xs font-bold uppercase tracking-widest mb-8 border border-white/10">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                #1 Salon Booking Platform
            </div>
            
            <h1 class="text-5xl lg:text-7xl font-black text-white leading-[1.1] mb-6 tracking-tight">
                Elevate Your <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-white">Style & Beauty</span>
            </h1>
            
            <p class="text-lg text-indigo-100/80 mb-10 leading-relaxed max-w-xl mx-auto lg:mx-0 font-light">
                Experience the pinnacle of convenience. Discover verified experts, book instantly, and skip the wait with our live queue tracking.
            </p>

            <!-- Floating Search Bar -->
            <div class="glass-panel p-3 rounded-[24px] flex flex-col md:flex-row gap-2 max-w-2xl mx-auto lg:mx-0 transform transition-all hover:scale-[1.01]">
                <div class="flex-1 bg-gray-50/50 rounded-2xl px-5 py-3 hover:bg-gray-50 transition border border-transparent hover:border-indigo-200">
                    <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-wider mb-1">Location State</label>
                    <select id="home-state" class="custom-select w-full bg-transparent border-none p-0 text-gray-800 font-bold focus:ring-0 cursor-pointer">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-px bg-gray-200 hidden md:block my-2"></div>

                <div class="flex-1 bg-gray-50/50 rounded-2xl px-5 py-3 hover:bg-gray-50 transition border border-transparent hover:border-indigo-200">
                    <label class="block text-[10px] font-black text-indigo-900 uppercase tracking-wider mb-1">City / Area</label>
                    <select id="home-city" class="custom-select w-full bg-transparent border-none p-0 text-gray-800 font-bold focus:ring-0 cursor-pointer">
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <button onclick="filterSaloons()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 md:py-0 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2 group">
                    <span>Search</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>

            <div class="mt-8 flex items-center justify-center lg:justify-start gap-8 text-white/60 text-sm font-medium">
                <span class="flex items-center gap-2"><i class="fas fa-check-circle text-amber-400"></i> Verified Pros</span>
                <span class="flex items-center gap-2"><i class="fas fa-check-circle text-amber-400"></i> Instant Booking</span>
                <span class="flex items-center gap-2"><i class="fas fa-check-circle text-amber-400"></i> Pay at Venue</span>
            </div>
        </div>

        <!-- Hero Visuals -->
        <div class="relative hidden lg:block h-[600px]">
            <!-- Main Image Card -->
            <div class="absolute top-10 right-10 w-[400px] h-[500px] bg-gray-900 rounded-[40px] overflow-hidden shadow-2xl border-[8px] border-white/10 float-item z-10">
                <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Salon Experience" class="w-full h-full object-cover opacity-90 hover:scale-110 transition-transform duration-700">
                
                <!-- Floating Badge -->
                <div class="absolute bottom-8 left-8 right-8 glass-panel p-4 rounded-2xl flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Average Rating</p>
                        <div class="flex items-center gap-1 text-amber-400 text-lg">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="text-indigo-900 font-black ml-1">4.8</span>
                        </div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
            </div>

            <!-- Secondary Floating Card -->
            <div class="absolute top-40 left-0 w-[260px] glass-card-dark p-6 rounded-[30px] float-item-delay z-20 border border-white/20">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-amber-400 flex items-center justify-center text-indigo-900 text-xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold">Queue Tracker</p>
                        <p class="text-indigo-200 text-xs">Live Updates</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full w-2/3 bg-amber-400 rounded-full"></div>
                    </div>
                    <div class="flex justify-between text-xs text-indigo-200">
                        <span>Your Turn</span>
                        <span class="text-white font-bold">15 mins</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Ticker -->
<div class="bg-indigo-950 border-y border-white/5 py-12 relative overflow-hidden">
    <div class="container mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 relative z-10">
        <div class="text-center">
            <h3 class="text-4xl font-black text-white mb-1">{{ $stats['total_saloons'] }}+</h3>
            <p class="text-indigo-300 text-sm uppercase tracking-widest font-bold">Premium Salons</p>
        </div>
        <div class="text-center">
            <h3 class="text-4xl font-black text-white mb-1">{{ $stats['total_services'] }}+</h3>
            <p class="text-indigo-300 text-sm uppercase tracking-widest font-bold">Services</p>
        </div>
        <div class="text-center">
            <h3 class="text-4xl font-black text-white mb-1">{{ $stats['happy_customers'] }}+</h3>
            <p class="text-indigo-300 text-sm uppercase tracking-widest font-bold">Happy Clients</p>
        </div>
        <div class="text-center">
            <h3 class="text-4xl font-black text-white mb-1">4.9/5</h3>
            <p class="text-indigo-300 text-sm uppercase tracking-widest font-bold">Average Rating</p>
        </div>
    </div>
</div>

<!-- Categories -->
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div>
                <span class="text-indigo-600 font-bold tracking-widest uppercase text-sm">Discover Excellence</span>
                <h2 class="text-4xl font-black text-gray-900 mt-2">Browse by Category</h2>
            </div>
            <a href="{{ route('saloons') }}" class="group flex items-center gap-2 font-bold text-indigo-600 hover:text-indigo-800 transition">
                <span>View Full Catalog</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('saloons') }}" class="group bg-white rounded-[24px] p-6 text-center hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 border border-gray-100 hover:border-indigo-100 hover:-translate-y-1">
                    <div class="w-16 h-16 mx-auto bg-gray-50 rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                        {{ $category->icon }}
                    </div>
                    <h3 class="font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Collection -->
<section class="py-24 bg-white relative">
    <div class="absolute top-0 left-0 w-full h-1/2 bg-gray-50 -skew-y-2 transform origin-top-left z-0"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-black text-gray-900 mb-4">Featured Collections</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg">Handpicked selection of the most highly-rated and premium salons in your area.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredSaloons as $saloon)
                <div class="bg-white rounded-[24px] overflow-hidden shadow-lg shadow-gray-200/50 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 group border border-gray-100">
                    <div class="h-64 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-60 group-hover:opacity-40 transition-opacity"></div>
                        @if($saloon->images && count($saloon->images) > 0)
                            <img src="{{ asset('storage/' . $saloon->images[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-6xl font-black">
                                {{ substr($saloon->name, 0, 1) }}
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-4 left-4 z-20 flex gap-2">
                             @if($saloon->subscription_level == 'platinum')
                                <span class="bg-white/90 backdrop-blur text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider text-indigo-900 flex items-center gap-1 shadow-lg">
                                    <i class="fas fa-crown text-amber-400"></i> Platinum
                                </span>
                             @endif
                        </div>

                        <div class="absolute bottom-4 left-4 right-4 z-20">
                            <h3 class="text-white text-xl font-bold truncate mb-1">{{ $saloon->name }}</h3>
                            <div class="flex items-center text-white/80 text-sm">
                                <i class="fas fa-map-pin mr-1.5 text-amber-400"></i> {{ $saloon->city }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center gap-1.5 text-amber-400 text-sm font-bold">
                                <span class="text-xl">{{ number_format($saloon->rating, 1) }}</span>
                                <div class="flex gap-0.5 text-xs">
                                    @for($i=0; $i<5; $i++)
                                        <i class="fas fa-star {{ $i < $saloon->rating ? '' : 'text-gray-200' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-gray-400 font-normal ml-1">({{ $saloon->total_reviews }})</span>
                            </div>
                        </div>

                        <a href="{{ route('saloon.detail', $saloon->slug) }}" class="block w-full text-center bg-gray-50 hover:bg-indigo-600 hover:text-white text-gray-900 font-bold py-3.5 rounded-xl transition-all duration-300">
                            Book Appointment
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-indigo-900 relative overflow-hidden">
    <!-- Geometric Background Pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    <div class="container mx-auto px-6 relative z-10 text-center">
        <h2 class="text-4xl md:text-6xl font-black text-white mb-6">Ready to Transform Your Business?</h2>
        <p class="text-xl text-indigo-200 mb-10 max-w-2xl mx-auto">Join thousands of salon owners who have doubled their bookings with our platform.</p>
        
        <div class="flex flex-col md:flex-row justify-center gap-6">
            <a href="{{ route('saloon.register') }}" class="px-8 py-4 bg-amber-400 hover:bg-amber-300 text-indigo-900 font-black rounded-xl text-lg transition shadow-xl shadow-amber-400/20 transform hover:-translate-y-1">
                Register Your Salon
            </a>
            <a href="{{ route('saloon.login') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur border border-white/20 text-white font-bold rounded-xl text-lg transition">
                Partner Login
            </a>
        </div>
    </div>
</section>

<script>
    function filterSaloons() {
        const state = document.getElementById('home-state').value;
        const city = document.getElementById('home-city').value;
        let url = new URL('{{ route('saloons') }}');
        if(state) url.searchParams.append('state', state);
        if(city) url.searchParams.append('city', city);
        window.location.href = url.href;
    }
</script>
@endsection

