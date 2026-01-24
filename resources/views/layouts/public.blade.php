<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Saloon Management System')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        
        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --accent: #fbbf24;
        }

        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #f8fafc;
            display: flex;
            flex-col;
            min-height: 100vh;
        }

        /* Fixed Navigation */
        .main-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(30, 27, 75, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white !important;
            text-decoration: none;
            font-weight: 900;
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links a {
            color: white !important;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--accent) !important;
        }

        .cta-group {
            display: flex;
            align-items: center;
            gap: 1rem;
            border-left: 1px solid rgba(255,255,255,0.2);
            padding-left: 1.5rem;
        }

        .nav-btn {
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-btn-accent { background: var(--accent); color: #1e1b4b !important; }
        .nav-btn-white { background: white; color: #1e1b4b !important; }
        
        main {
            padding-top: 100px; /* Space for fixed nav */
            flex-grow: 1;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="main-nav">
        <a href="{{ route('home') }}" class="nav-logo">
            <i class="fas fa-cut" style="color: var(--accent);"></i>
            <span>Saloon<span style="color: var(--accent);">Manager</span></span>
        </a>
        
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('saloons') }}">Saloons</a>
            
            <div class="cta-group">
                @auth
                    <a href="{{ 
                        auth()->user()->isSuperAdmin() ? route('admin.dashboard') : 
                        (auth()->user()->isSaloonAdmin() ? route('saloon-admin.dashboard') : route('user.dashboard')) 
                    }}" class="nav-btn nav-btn-accent">
                        <i class="fas fa-th-large mr-2"></i>Dashboard
                    </a>
                @else
                    <div class="flex items-center gap-6">
                        <a href="{{ route('saloon.login') }}" class="font-bold text-xs text-indigo-200 hover:text-white uppercase tracking-wider transition">Saloon Login</a>
                        <a href="{{ route('saloon.register') }}" class="nav-btn nav-btn-accent shadow-lg shadow-yellow-500/20">Register Salon</a>
                        
                        <div class="w-px h-8 bg-indigo-500/30"></div>
                        
                        <a href="{{ route('login') }}" class="font-bold text-xs text-indigo-200 hover:text-white uppercase tracking-wider transition">User Sign In</a>
                        <a href="{{ route('register') }}" class="nav-btn nav-btn-white shadow-lg">Join Now</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="container mx-auto px-6 pt-6">
                <div class="bg-indigo-600 text-white p-4 rounded-2xl shadow-lg font-bold flex items-center justify-between">
                    <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100">×</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mx-auto px-6 pt-6">
                <div class="bg-red-500 text-white p-4 rounded-2xl shadow-lg font-bold">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-auto">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-cut text-2xl"></i>
                        <span class="font-bold text-xl">Saloon Manager</span>
                    </div>
                    <p class="text-gray-400">Your trusted partner for beauty and grooming services.</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="{{ route('saloons') }}" class="text-gray-400 hover:text-white">Salons</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">For Business</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Register Your Salon</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Contact Us</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i>info@saloonmanager.com</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Saloon Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
