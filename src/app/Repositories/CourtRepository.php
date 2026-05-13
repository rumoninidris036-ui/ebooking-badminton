<?php

namespace App\Repositories;

use App\Models\Court;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourtRepository
{
    public function paginatePublic(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return Court::query()
            ->with(['owner:id,name', 'schedules', 'facilities:id,name,icon'])
            ->withCount('bookings')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->when($filters['location'] ?? null, fn ($query, $location) => $query->where('location', 'like', '%'.$location.'%'))
            ->when($filters['min_price'] ?? null, fn ($query, $minPrice) => $query->where('price_per_hour', '>=', $minPrice))
            ->when($filters['max_price'] ?? null, fn ($query, $maxPrice) => $query->where('price_per_hour', '<=', $maxPrice))
            ->when($filters['facility_ids'] ?? null, fn ($query, $facilityIds) => $query->whereHas('facilities', fn ($facilityQuery) => $facilityQuery->whereIn('facilities.id', $facilityIds)))
            ->where('status', 'active')
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForOwner(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Court::query()
            ->with(['schedules', 'facilities'])
            ->when($user->role === 'owner', fn ($query) => $query->where('owner_id', $user->id))
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $attributes): Court
    {
        return Court::query()->create($attributes);
    }

    public function findPublic(int $id): ?Court
    {
        return Court::query()
            ->with(['owner:id,name', 'schedules', 'facilities:id,name,icon'])
            ->withCount('bookings')
            ->where('status', 'active')
            ->find($id);
    }

    public function findOwnedByUser(int $courtId, User $user): ?Court
    {
        return Court::query()
            ->with(['owner:id,name', 'schedules', 'facilities'])
            ->when($user->role === 'owner', fn ($query) => $query->where('owner_id', $user->id))
            ->find($courtId);
    }

    public function update(Court $court, array $attributes): Court
    {
        $court->update($attributes);

        return $court->fresh(['owner:id,name', 'schedules']);
    }

    public function delete(Court $court): void
    {
        $court->delete();
    }
}
