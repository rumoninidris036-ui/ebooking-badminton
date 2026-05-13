<?php

namespace App\Repositories;

use App\Models\Court;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository
{
    public function listForPublic(?int $courtId = null): Collection
    {
        return Review::query()
            ->with(['user:id,name', 'court:id,name,location'])
            ->when($courtId, fn ($query) => $query->where('court_id', $courtId))
            ->latest()
            ->get();
    }

    public function create(array $attributes): Review
    {
        return Review::query()->create($attributes);
    }

    public function averageForCourt(Court $court): float
    {
        return (float) Review::query()
            ->where('court_id', $court->id)
            ->avg('rating');
    }
}
