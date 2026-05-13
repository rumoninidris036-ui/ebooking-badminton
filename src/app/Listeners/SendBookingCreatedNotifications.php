<?php

namespace App\Listeners;

use App\Events\Booking\BookingCreated;
use App\Models\User;
use App\Services\NotificationService;

class SendBookingCreatedNotifications
{
    public function __construct(
        protected NotificationService $notifications,
    ) {
    }

    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;

        $this->notifications->sendInApp(
            $booking->user,
            'booking_created',
            'Booking created',
            'Your booking '.$booking->booking_code.' has been created.'
        );

        $owner = User::query()->find($booking->court?->owner_id);

        if ($owner) {
            $this->notifications->sendInApp(
                $owner,
                'booking_created',
                'New booking received',
                'A new booking '.$booking->booking_code.' was created for '.$booking->court?->name.'.'
            );
        }
    }
}
