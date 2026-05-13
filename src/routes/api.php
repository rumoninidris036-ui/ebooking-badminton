<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CourtController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'API is ready.',
        'data' => [
            'app' => config('app.name'),
            'environment' => config('app.env'),
        ],
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    });
});

Route::get('/courts', [CourtController::class, 'index'])->name('api.courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('api.courts.show');
Route::get('/fields', [CourtController::class, 'index'])->name('api.fields.index');
Route::get('/fields/{court}', [CourtController::class, 'show'])->name('api.fields.show');
Route::get('/facilities', [FacilityController::class, 'index'])->name('api.facilities.index');
Route::get('/bookings/availability', [BookingController::class, 'availability'])->name('api.bookings.availability');
Route::get('/recommendations', [RecommendationController::class, 'index'])->name('api.recommendations.index');
Route::get('/reviews', [ReviewController::class, 'index'])->name('api.reviews.index');

Route::middleware(['auth:sanctum', 'role:admin,owner'])->group(function () {
    Route::post('/courts', [CourtController::class, 'store'])->name('api.courts.store');
    Route::put('/courts/{court}', [CourtController::class, 'update'])->name('api.courts.update');
    Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('api.courts.destroy');
    Route::post('/facilities', [FacilityController::class, 'store'])->name('api.facilities.store');
    Route::put('/facilities/{facility}', [FacilityController::class, 'update'])->name('api.facilities.update');
    Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('api.facilities.destroy');
    Route::get('/reports', [ReportController::class, 'index'])->name('api.reports.index');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('api.bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('api.bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('api.bookings.cancel');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('api.reviews.store');
});

Route::middleware(['auth:sanctum', 'role:admin,owner'])->group(function () {
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('api.bookings.confirm');
    Route::post('/bookings/{booking}/finish', [BookingController::class, 'finish'])->name('api.bookings.finish');
});
