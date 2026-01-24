<div class="topbar">
    <div class="flex items-center gap-4">
        <button id="mobile-menu-toggle" class="lg:hidden text-gray-600 hover:text-gray-900">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                @yield('page-title', 'Dashboard')
            </h1>
            <p class="text-sm text-gray-500">@yield('page-subtitle', 'Welcome back!')</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Notifications -->
        <div class="relative">
            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
        </div>

        @php
            $profileRoute = '#';
            if(auth()->check()) {
                if(auth()->user()->isSuperAdmin()) $profileRoute = route('admin.profile.edit');
                elseif(auth()->user()->isSaloonAdmin()) $profileRoute = route('saloon-admin.profile.edit');
                else $profileRoute = route('user.profile.edit');
            }
        @endphp
        <!-- User Profile Dropdown -->
        <a href="{{ $profileRoute }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-all">
            <div class="text-right">
                <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">
                    @if(auth()->user()->isSuperAdmin())
                        Super Admin
                    @elseif(auth()->user()->isSaloonAdmin())
                        Saloon Admin
                    @else
                        Customer
                    @endif
                </p>
            </div>
            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </a>
    </div>
</div>
