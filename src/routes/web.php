<?php

use App\Http\Controllers\Page\AuthPageController;
use App\Http\Controllers\Page\BookingPageController;
use App\Http\Controllers\Page\CourtPageController;
use App\Http\Controllers\Page\LandingPageController;
use App\Http\Controllers\Page\OperationsPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPageController::class)->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthPageController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthPageController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthPageController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthPageController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [BookingPageController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingPageController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingPageController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/cancel', [BookingPageController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/operations/schedules', [OperationsPageController::class, 'schedules'])->name('operations.schedules');
    Route::get('/operations/reviews', [OperationsPageController::class, 'reviews'])->name('operations.reviews');
    Route::get('/operations/notifications', [OperationsPageController::class, 'notifications'])->name('operations.notifications');
    Route::get('/operations/reports', [OperationsPageController::class, 'reports'])->name('operations.reports');
    Route::get('/operations/profile', [OperationsPageController::class, 'profile'])->name('operations.profile');
    Route::post('/logout', [AuthPageController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::post('/bookings/{booking}/confirm', [BookingPageController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/finish', [BookingPageController::class, 'finish'])->name('bookings.finish');
    Route::get('/operations/owner/revenue', [OperationsPageController::class, 'ownerRevenue'])->name('operations.owner.revenue');
    Route::get('/operations/owner/requests', [OperationsPageController::class, 'ownerRequests'])->name('operations.owner.requests');
});

Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::get('/courts', [CourtPageController::class, 'index'])->name('courts.index');
    Route::get('/courts/create', [CourtPageController::class, 'create'])->name('courts.create');
    Route::post('/courts', [CourtPageController::class, 'store'])->name('courts.store');
    Route::get('/courts/{court}/edit', [CourtPageController::class, 'edit'])->name('courts.edit');
    Route::put('/courts/{court}', [CourtPageController::class, 'update'])->name('courts.update');
    Route::delete('/courts/{court}', [CourtPageController::class, 'destroy'])->name('courts.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/operations/admin/users', [OperationsPageController::class, 'adminUsers'])->name('operations.admin.users');
    Route::get('/operations/admin/owners', [OperationsPageController::class, 'adminOwners'])->name('operations.admin.owners');
    Route::get('/operations/admin/analytics', [OperationsPageController::class, 'adminAnalytics'])->name('operations.admin.analytics');
    Route::get('/operations/admin/recommendations', [OperationsPageController::class, 'adminRecommendations'])->name('operations.admin.recommendations');
    Route::get('/operations/admin/transactions', [OperationsPageController::class, 'adminTransactions'])->name('operations.admin.transactions');
    Route::get('/operations/admin/monitoring', [OperationsPageController::class, 'adminMonitoring'])->name('operations.admin.monitoring');
    Route::get('/operations/admin/settings', [OperationsPageController::class, 'adminSettings'])->name('operations.admin.settings');
});
