<?php

namespace App\Services;

use App\Models\Court;
use App\Models\Review;
use App\Models\User;
use App\Repositories\ReviewRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    public function __construct(
        protected ReviewRepository $reviews,
    ) {
    }

    public function listPublic(?int $courtId = null): Collection
    {
        return $this->reviews->listForPublic($courtId);
    }

    public function create(array $attributes, User $user): Review
    {
        $hasCompletedBooking = $user->bookings()
            ->where('court_id', $attributes['court_id'])
            ->where('status', 'finished')
            ->exists();

        if (! $hasCompletedBooking) {
            throw ValidationException::withMessages([
                'court_id' => ['Only completed bookings can submit reviews.'],
            ]);
        }

        $review = $this->reviews->create([
            'user_id' => $user->id,
            'court_id' => $attributes['court_id'],
            'rating' => $attributes['rating'],
            'comment' => $attributes['comment'],
        ]);

        $court = Court::query()->findOrFail($attributes['court_id']);
        $court->update([
            'rating' => $this->reviews->averageForCourt($court),
        ]);

        return $review->fresh(['user:id,name', 'court:id,name,location']);
    }
}
