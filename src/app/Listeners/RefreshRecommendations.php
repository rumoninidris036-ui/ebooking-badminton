<?php

namespace App\Listeners;

use App\Events\Booking\BookingCanceled;
use App\Events\Booking\BookingCreated;
use App\Services\RecommendationService;

class RefreshRecommendations
{
    public function __construct(
        protected RecommendationService $recommendationService,
    ) {
    }

    public function handle(BookingCreated|BookingCanceled $event): void
    {
        $this->recommendationService->refreshForUser($event->booking->user);
    }
}
