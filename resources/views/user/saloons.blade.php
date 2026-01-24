@extends('layouts.public')

@section('title', 'Browse Salons')

@push('styles')
<style>
    .listing-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        padding: 60px 0;
        color: white;
    }
    .filter-bar {
        background: white;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        margin-top: -40px;
        position: relative;
        z-index: 10;
        border: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }
    .saloon-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .saloon-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .queue-badge {
        background: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }
</style>
@endpush

@section('content')
<div>
    <!-- Hero Header -->
    <div class="listing-hero">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-black mb-2">Available Salons</h1>
            <p class="text-indigo-200">Discover and book top-rated professionals in {{ request('city', 'your area') }}</p>
        </div>
    </div>

    <div class="container mx-auto px-6">
        <!-- Subtle Filter Bar -->
        <div class="filter-bar">
            <form action="{{ route('saloons') }}" method="GET" class="flex flex-wrap gap-4 items-center w-full">
                <div class="relative flex-grow max-w-md">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-none bg-gray-50 focus:ring-2 focus:ring-indigo-500 font-medium" 
                        placeholder="Search by name or address...">
                </div>
                
                <select name="state" class="px-4 py-3 rounded-xl border-none bg-gray-50 focus:ring-2 focus:ring-indigo-500 font-bold text-sm">
                    <option value="">All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                    @endforeach
                </select>

                <select name="city" class="px-4 py-3 rounded-xl border-none bg-gray-50 focus:ring-2 focus:ring-indigo-500 font-bold text-sm">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>

                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    UPDATE
                </button>
                
                @if(request()->hasAny(['search', 'state', 'city']))
                    <a href="{{ route('saloons') }}" class="text-gray-400 hover:text-red-500 font-bold text-xs uppercase tracking-widest">Clear</a>
                @endif
            </form>
        </div>

        <!-- Results Grid -->
        <div class="py-12">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight">
                    {{ $saloons->total() }} Salons Found
                </h2>
            </div>

            @if($saloons->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($saloons as $saloon)
                        <div class="saloon-card">
                            <!-- Premium Image Section -->
                            <div class="h-56 relative overflow-hidden bg-indigo-900">
                                @if($saloon->images && count($saloon->images) > 0)
                                    <img src="{{ asset('storage/' . $saloon->images[0]) }}" alt="{{ $saloon->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white/20 text-7xl font-black">
                                        {{ substr($saloon->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <!-- Floating Badges -->
                                <div class="absolute top-4 left-4">
                                    <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg flex items-center gap-1.5">
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        <span class="text-xs font-black text-gray-800">{{ number_format($saloon->rating, 1) }}</span>
                                    </div>
                                </div>

                                @if($saloon->subscription_level == 'premium')
                                    <div class="absolute top-4 right-4">
                                        <div class="bg-yellow-400 text-indigo-900 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                            <i class="fas fa-crown mr-1"></i> Featured
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-black text-gray-900 leading-tight mb-1">{{ $saloon->name }}</h3>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                                            <i class="fas fa-map-marker-alt text-indigo-500 mr-1"></i> {{ $saloon->city }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Queue Insights -->
                                @php $stats = $saloon->getQueueStats(); @endphp
                                <div class="bg-gray-50 rounded-2xl p-4 mb-6 space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-black text-gray-400 uppercase">Wait Time</span>
                                        <span class="text-xs font-black text-indigo-600">{{ $stats['wait_time_minutes'] }} Mins</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-black text-gray-400 uppercase">Reach At</span>
                                        <span class="text-xs font-black text-gray-800">{{ $stats['expected_reach_time'] }}</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 rounded-full" style="width: {{ min(100, ($stats['waiting_count'] * 20)) }}%"></div>
                                    </div>
                                </div>

                                <a href="{{ route('saloon.detail', $saloon->slug) }}" class="block w-full text-center bg-indigo-600 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $saloons->links() }}
                </div>
            @else
                <div class="text-center py-24 bg-white rounded-[40px] border border-dashed border-gray-200">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 mb-2">No Salons Found</h3>
                    <p class="text-gray-400 max-w-sm mx-auto font-medium">We couldn't find any results for your search. Try adjusting the city or state.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
