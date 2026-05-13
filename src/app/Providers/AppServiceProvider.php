<?php

namespace App\Providers;

use App\Events\Booking\BookingCanceled;
use App\Events\Booking\BookingCreated;
use App\Listeners\RefreshRecommendations;
use App\Listeners\SendBookingCanceledNotifications;
use App\Listeners\SendBookingCreatedNotifications;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(BookingCreated::class, SendBookingCreatedNotifications::class);
        Event::listen(BookingCreated::class, RefreshRecommendations::class);
        Event::listen(BookingCanceled::class, SendBookingCanceledNotifications::class);
        Event::listen(BookingCanceled::class, RefreshRecommendations::class);
    }
}
