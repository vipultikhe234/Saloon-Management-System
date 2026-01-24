<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $isAdminLogin = $request->routeIs('admin.login');
        $isSaloonLogin = $request->routeIs('saloon.login');
        return view('auth.login', compact('isAdminLogin', 'isSaloonLogin'));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        // Specific handling for route-based restrictions if needed
        if ($request->routeIs('admin.login') && !$user->isSuperAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Unauthorized access.');
        }

        if ($request->routeIs('saloon.login') && !$user->isSaloonAdmin()) {
            Auth::logout();
            return redirect()->route('saloon.login')->with('error', 'Unauthorized access.');
        }

        // Redirect based on user role
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isSaloonAdmin()) {
            return redirect()->route('saloon-admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
