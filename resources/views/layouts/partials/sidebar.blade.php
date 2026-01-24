<div class="sidebar">
    <div class="sidebar-header">
        <h2 class="text-white font-bold text-xl flex items-center gap-2">
            <i class="fas fa-cut"></i>
            <span>Saloon Manager</span>
        </h2>
        <p class="text-indigo-200 text-sm mt-1">
            @if(auth()->user()->isSuperAdmin())
                Super Admin Panel
            @elseif(auth()->user()->isSaloonAdmin())
                Saloon Admin Panel
            @else
                User Dashboard
            @endif
        </p>
    </div>

    <nav class="sidebar-menu">
        @if(auth()->user()->isSuperAdmin())
            {{-- Super Admin Menu --}}
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.saloons.index') }}" class="{{ request()->routeIs('admin.saloons.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i>
                <span>Saloons</span>
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Subscriptions</span>
            </a>
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                User Management
            </div>
            <a href="{{ route('admin.users.admins') }}" class="{{ request()->routeIs('admin.users.admins') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                <span>System Admins</span>
            </a>

            <a href="#" class="">
                <i class="fas fa-chart-line"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>

        @elseif(auth()->user()->isSaloonAdmin())
            {{-- Saloon Admin Menu --}}
            <a href="{{ route('saloon-admin.dashboard') }}" class="{{ request()->routeIs('saloon-admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('saloon-admin.queue.index') }}" class="{{ request()->routeIs('saloon-admin.queue.*') ? 'active' : '' }}">
                <i class="fas fa-list-ol"></i>
                <span>Live Queue</span>
            </a>
            <a href="{{ route('saloon-admin.services.index') }}" class="{{ request()->routeIs('saloon-admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-concierge-bell"></i>
                <span>Services</span>
            </a>
            <a href="{{ route('saloon-admin.staff.index') }}" class="{{ request()->routeIs('saloon-admin.staff.*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                <span>Staff</span>
            </a>
            <a href="{{ route('saloon-admin.appointments.index') }}" class="{{ request()->routeIs('saloon-admin.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span>
            </a>
            <a href="#" class="">
                <i class="fas fa-star"></i>
                <span>Reviews</span>
            </a>
            <a href="{{ route('saloon-admin.coupons.index') }}" class="{{ request()->routeIs('saloon-admin.coupons.*') ? 'active' : '' }}">
                <i class="fas fa-percentage"></i>
                <span>Coupons</span>
            </a>

            <a href="#" class="">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('saloon-admin.profile.edit') }}" class="{{ request()->routeIs('saloon-admin.profile.edit') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>

        @else
            {{-- User Menu --}}
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>My Dashboard</span>
            </a>
            <a href="{{ route('saloons') }}" class="{{ request()->routeIs('saloons') ? 'active' : '' }}">
                <i class="fas fa-store"></i>
                <span>Browse Saloons</span>
            </a>
            <a href="{{ route('user.appointments') }}" class="{{ request()->routeIs('user.appointments') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>My Appointments</span>
            </a>
            <a href="#" class="">
                <i class="fas fa-heart"></i>
                <span>Favorites</span>
            </a>
            <a href="{{ route('user.profile.edit') }}" class="{{ request()->routeIs('user.profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        @endif

        <div class="border-t border-gray-700 my-4"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-6 py-3 text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-all duration-300 flex items-center gap-3">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>
