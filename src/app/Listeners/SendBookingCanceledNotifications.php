<?php

namespace App\Listeners;

use App\Events\Booking\BookingCanceled;
use App\Models\User;
use App\Services\NotificationService;

class SendBookingCanceledNotifications
{
    public function __construct(
        protected NotificationService $notifications,
    ) {
    }

    public function handle(BookingCanceled $event): void
    {
        $booking = $event->booking;

        $this->notifications->sendInApp(
            $booking->user,
            'booking_canceled',
            'Booking canceled',
            'Your booking '.$booking->booking_code.' has been canceled.'
        );

        $owner = User::query()->find($booking->court?->owner_id);

        if ($owner) {
            $this->notifications->sendInApp(
                $owner,
                'booking_canceled',
                'Booking canceled',
                'Booking '.$booking->booking_code.' for '.$booking->court?->name.' was canceled.'
            );
        }
    }
}
