<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\SaloonController as SuperAdminSaloonController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SaloonAdmin\DashboardController as SaloonAdminDashboard;
use App\Http\Controllers\SaloonAdmin\ServiceController;
use App\Http\Controllers\SaloonAdmin\StaffController;
use App\Http\Controllers\SaloonAdmin\AppointmentController as SaloonAdminAppointment;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\BookingController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/saloons', [HomeController::class, 'saloons'])->name('saloons');
Route::get('/saloon/{slug}', [HomeController::class, 'saloonDetail'])->name('saloon.detail');

// Booking Routes (Guest accessible)
Route::get('user/book/{saloon}', [BookingController::class, 'create'])->name('user.booking.create');
Route::post('user/book', [BookingController::class, 'store'])->name('user.booking.store');

// Central Dashboard Redirector (Fixes Breeze redirects)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isSuperAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isSaloonAdmin()) {
        return redirect()->route('saloon-admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

// User/Customer Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', function() { return redirect()->route('user.dashboard'); });
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // NEW Queue System
    Route::post('/queue/join/{saloon}', [\App\Http\Controllers\User\QueueController::class, 'join'])->name('queue.join');
    Route::get('/queue/status/{appointment}', [\App\Http\Controllers\User\QueueController::class, 'status'])->name('queue.status');

    Route::get('/appointments', [BookingController::class, 'index'])->name('appointments');
    Route::post('/appointment/{appointment}/cancel', [BookingController::class, 'cancel'])->name('appointment.cancel');
});

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    
    // Saloon Management
    Route::resource('saloons', SuperAdminSaloonController::class);
    Route::post('saloons/{saloon}/toggle-status', [SuperAdminSaloonController::class, 'toggleStatus'])->name('saloons.toggle-status');
    Route::post('saloons/{saloon}/verify', [SuperAdminSaloonController::class, 'verify'])->name('saloons.verify');
    Route::post('saloons/{saloon}/login-as', [SuperAdminSaloonController::class, 'loginAsOwner'])->name('saloons.login-as');
    
    // Subscription Management
    Route::get('subscriptions', [\App\Http\Controllers\SuperAdmin\SubscriptionManagementController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions/{saloon}/extend', [\App\Http\Controllers\SuperAdmin\SubscriptionManagementController::class, 'extend'])->name('subscriptions.extend');
    
    // User Management (Restricted: ONLY Admins)
    Route::get('users/admins', [SuperAdminUserController::class, 'admins'])->name('users.admins');
    // Customers route REMOVED for security
    Route::resource('users', SuperAdminUserController::class)->except(['index']); 
    Route::post('users/{user}/toggle-status', [SuperAdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Saloon Admin Routes
Route::middleware(['auth', 'role:saloon_admin'])->prefix('saloon-admin')->name('saloon-admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('saloon-admin.dashboard'); });
    Route::get('/dashboard', [SaloonAdminDashboard::class, 'index'])->name('dashboard');
    
    // Queue Management (New)
    Route::get('/queue', [\App\Http\Controllers\SaloonAdmin\QueueController::class, 'index'])->name('queue.index');
    Route::post('/queue/{appointment}/done', [\App\Http\Controllers\SaloonAdmin\QueueController::class, 'markDone'])->name('queue.done');
    Route::post('/queue/{appointment}/start', [\App\Http\Controllers\SaloonAdmin\QueueController::class, 'startServing'])->name('queue.start');
    Route::post('/queue/{appointment}/cancel', [\App\Http\Controllers\SaloonAdmin\QueueController::class, 'cancel'])->name('queue.cancel');

    // ... (Existing Routes)
    Route::get('/saloon/create', [\App\Http\Controllers\SaloonAdmin\SaloonProfileController::class, 'create'])->name('saloon.create');
    Route::post('/saloon', [\App\Http\Controllers\SaloonAdmin\SaloonProfileController::class, 'store'])->name('saloon.store');
    Route::get('/saloon/edit', [\App\Http\Controllers\SaloonAdmin\SaloonProfileController::class, 'edit'])->name('saloon.edit');
    Route::put('/saloon/{saloon}', [\App\Http\Controllers\SaloonAdmin\SaloonProfileController::class, 'update'])->name('saloon.update');
    
    Route::resource('services', ServiceController::class);
    Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    
    Route::resource('staff', StaffController::class);
    Route::post('staff/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
    Route::post('staff/{staff}/assign-services', [StaffController::class, 'assignServices'])->name('staff.assign-services');
    
    Route::resource('appointments', SaloonAdminAppointment::class);
    Route::post('appointments/{appointment}/update-status', [SaloonAdminAppointment::class, 'updateStatus'])->name('appointments.update-status');

    // Subscription/Recharge Routes
    Route::get('/recharge', [\App\Http\Controllers\SaloonAdmin\SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::post('/recharge/pay', [\App\Http\Controllers\SaloonAdmin\SubscriptionController::class, 'showQR'])->name('subscription.recharge');
    Route::post('/recharge/verify', [\App\Http\Controllers\SaloonAdmin\SubscriptionController::class, 'verifyPayment'])->name('subscription.verify');
    Route::get('/recharge/success', [\App\Http\Controllers\SaloonAdmin\SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/recharge/cancel', [\App\Http\Controllers\SaloonAdmin\SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
