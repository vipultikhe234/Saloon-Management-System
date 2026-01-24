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
<!-- Hero Section -->
<div class="relative min-h-[800px] flex items-center overflow-hidden bg-[#1e1b4b]">
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-[30%] -left-[10%] w-[700px] h-[700px] rounded-full bg-indigo-600/20 blur-[100px] animate-pulse"></div>
        <div class="absolute top-[20%] -right-[10%] w-[600px] h-[600px] rounded-full bg-purple-600/20 blur-[100px] animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10 pt-20">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left Content -->
            <div class="text-left space-y-8">
                <!-- Verified Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md shadow-lg shadow-indigo-500/10">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                    </span>
                    <span class="text-sm font-bold text-white tracking-wide uppercase">Verified Professionals Only</span>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-black text-white leading-[1.1] tracking-tight">
                    Book Your <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-500 filter drop-shadow-lg">Perfect Look</span>
                </h1>
                
                <p class="text-lg text-indigo-100/90 leading-relaxed max-w-lg font-medium">
                    The smartest way to book top-rated salons nearby. View live queue times, skip the wait, and experience premium grooming.
                </p>

                <!-- Search Bar -->
                <div class="bg-white p-2 rounded-2xl flex flex-col md:flex-row shadow-2xl shadow-indigo-900/50 max-w-xl border border-white/10 backdrop-blur-sm">
                    <div class="flex-1 px-5 py-3 border-b md:border-b-0 md:border-r border-gray-100 relative group">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 group-focus-within:text-indigo-600 transition-colors">State</label>
                        <select id="home-state" class="w-full bg-transparent border-none p-0 text-gray-900 font-bold focus:ring-0 cursor-pointer text-base outline-none">
                            <option value="">Choose State</option>
                            @foreach($states as $state)
                                <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 px-5 py-3 relative group">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 group-focus-within:text-indigo-600 transition-colors">City</label>
                        <select id="home-city" class="w-full bg-transparent border-none p-0 text-gray-900 font-bold focus:ring-0 cursor-pointer text-base outline-none">
                            <option value="">Choose City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-1.5">
                        <button onclick="filterSaloons()" class="h-full w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 rounded-xl font-bold transition-all duration-300 shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 transform active:scale-95">
                            FIND
                        </button>
                    </div>
                </div>
                
                <!-- Trust Indicators -->
                <div class="flex items-center gap-6 pt-4">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-[#1e1b4b] object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=64&h=64" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-[#1e1b4b] object-cover" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=64&h=64" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-[#1e1b4b] object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=64&h=64" alt="User">
                        <div class="w-10 h-10 rounded-full border-2 border-[#1e1b4b] bg-gray-800 text-white flex items-center justify-center text-xs font-bold">+2k</div>
                    </div>
                    <div class="text-sm font-medium text-indigo-200">
                        <span class="text-amber-400 font-bold">4.9/5</span> from happy customers
                    </div>
                </div>
            </div>

            <!-- Right Visual Composition -->
            <div class="relative h-[600px] w-full flex items-center justify-center lg:justify-end perspective-1000">
                <!-- Main Image Card -->
                <div class="relative w-[380px] h-[520px] bg-gray-900 rounded-[32px] overflow-hidden shadow-2xl shadow-indigo-900/50 border-[8px] border-white/5 transform rotate-[-3deg] hover:rotate-0 transition-all duration-500 ease-out z-10 group">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Salon Experience" class="w-full h-full object-cover opacity-90 group-hover:scale-110 transition-transform duration-700">
                    
                    <!-- Inner Content -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 z-20">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-white text-2xl font-bold">Luxe Barber</h3>
                            <span class="bg-amber-400 text-indigo-900 text-xs font-black px-2 py-1 rounded">4.8 ★</span>
                        </div>
                        <p class="text-white/70 text-sm">Premium grooming experience</p>
                    </div>
                </div>

                <!-- Floating Card: Live Queue (Top Right) -->
                <div class="absolute top-[10%] -right-4 lg:right-0 bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl shadow-xl z-20 animate-float-delayed w-64">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-10 h-10 rounded-full bg-amber-400 flex items-center justify-center text-indigo-900 text-lg shadow-lg">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold leading-tight">Queue Status</p>
                            <p class="text-indigo-200 text-xs">Live Updates</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-indigo-100 font-medium">
                            <span>Warning Time</span>
                            <span class="text-amber-300">12 mins</span>
                        </div>
                        <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full w-[70%] bg-gradient-to-r from-amber-300 to-amber-500 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Floating Card: Booking (Bottom Left) -->
                <div class="absolute bottom-[15%] left-0 lg:left-10 bg-white p-4 rounded-2xl shadow-xl shadow-black/20 z-30 animate-float w-[280px]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-gray-900 font-bold">Booking Confirmed</p>
                            <p class="text-green-600 text-xs font-bold flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Just now
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

