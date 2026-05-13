<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\CourtRepository;
use App\Repositories\RecommendationRepository;
use Illuminate\Database\Eloquent\Collection;

class RecommendationService
{
    public function __construct(
        protected RecommendationRepository $recommendations,
        protected CourtRepository $courts,
    ) {
    }

    public function listForUser(User $user, int $limit = 10): Collection
    {
        $existing = $this->recommendations->listForUser($user->id, $limit);

        if ($existing->isEmpty()) {
            $this->refreshForUser($user);
            $existing = $this->recommendations->listForUser($user->id, $limit);
        }

        return $existing;
    }

    public function refreshForUser(User $user): void
    {
        $user->loadMissing([
            'bookings.court.facilities:id,name',
            'reviews.court.facilities:id,name',
        ]);

        $courts = $this->courts->paginatePublic(50)->getCollection();
        $preferredLocations = $user->bookings
            ->map(fn ($booking) => strtolower((string) $booking->court?->location))
            ->filter()
            ->values();
        $preferredPrice = $user->bookings->avg(fn ($booking) => (float) $booking->price_per_hour) ?: null;
        $preferredFacilities = $user->bookings
            ->flatMap(fn ($booking) => $booking->court?->facilities?->pluck('name') ?? collect())
            ->merge(
                $user->reviews->flatMap(fn ($review) => $review->court?->facilities?->pluck('name') ?? collect())
            )
            ->map(fn ($name) => strtolower((string) $name))
            ->filter()
            ->countBy();

        $rows = $courts->map(function ($court) use ($user, $preferredLocations, $preferredPrice, $preferredFacilities) {
            $address = strtolower((string) ($user->address ?? ''));
            $courtLocation = strtolower((string) $court->location);
            $facilityNames = collect($court->facilities ?? [])->pluck('name')->map(fn ($name) => strtolower((string) $name));
            $locationScore = 10;
            $priceScore = 15;
            $facilityScore = 10;

            if ($address !== '' && str_contains($courtLocation, $address)) {
                $locationScore = 35;
            } elseif ($preferredLocations->contains(fn ($location) => $location !== '' && str_contains($courtLocation, $location))) {
                $locationScore = 28;
            }

            if ($preferredPrice) {
                $priceDistance = abs((float) $court->price_per_hour - $preferredPrice);
                $priceScore = max(8, 30 - ($priceDistance / 10000));
            } else {
                $priceScore = max(10, 30 - ((float) $court->price_per_hour / 10000));
            }

            if ($preferredFacilities->isNotEmpty()) {
                $facilityScore = min(25, $facilityNames->sum(fn ($name) => (int) ($preferredFacilities[$name] ?? 0)) * 5);
            }

            $ratingScore = ((float) $court->rating) * 6;
            $popularityScore = min(15, (int) ($court->bookings_count ?? 0) * 1.5);
            $reviewBoost = $user->reviews
                ->where('court_id', $court->id)
                ->avg('rating') ?: 0;

            return [
                'user_id' => $user->id,
                'court_id' => $court->id,
                'similarity_score' => round($locationScore + $priceScore + $facilityScore + $ratingScore + $popularityScore + ($reviewBoost * 4), 2),
                'created_at' => now(),
            ];
        })->sortByDesc('similarity_score')->values()->all();

        if ($rows !== []) {
            $this->recommendations->upsert($rows);
        }
    }
}
