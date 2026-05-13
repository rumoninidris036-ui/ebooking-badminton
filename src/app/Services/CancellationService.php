<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Repositories\CancellationRepository;

class CancellationService
{
    public function __construct(
        protected CancellationRepository $cancellations,
    ) {
    }

    public function store(Booking $booking, string $reason, User $user): void
    {
        $this->cancellations->create([
            'booking_id' => $booking->id,
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
            'cancelled_by' => $user->id,
        ]);
    }
}
