<?php

namespace App\Repositories;

use App\Models\Recommendation;
use Illuminate\Database\Eloquent\Collection;

class RecommendationRepository
{
    public function listForUser(int $userId, int $limit = 10): Collection
    {
        return Recommendation::query()
            ->with(['court.owner:id,name', 'court.facilities:id,name,icon', 'court.schedules'])
            ->where('user_id', $userId)
            ->orderByDesc('similarity_score')
            ->limit($limit)
            ->get();
    }

    public function upsert(array $rows): void
    {
        Recommendation::query()->upsert(
            $rows,
            ['user_id', 'court_id'],
            ['similarity_score', 'created_at']
        );
    }
}
