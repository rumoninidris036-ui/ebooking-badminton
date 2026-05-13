<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    public function paginateForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Booking::query()
            ->with(['court:id,name,location', 'user:id,name', 'cancellation'])
            ->when(in_array($user->role, ['user', 'customer'], true), fn ($query) => $query->where('user_id', $user->id))
            ->when($user->role === 'owner', fn ($query) => $query->whereHas('court', fn ($courtQuery) => $courtQuery->where('owner_id', $user->id)))
            ->latest('booking_date')
            ->latest('start_time')
            ->paginate($perPage);
    }

    public function create(array $attributes): Booking
    {
        return Booking::query()->create($attributes);
    }

    public function findVisibleByUser(int $id, User $user): ?Booking
    {
        return Booking::query()
            ->with(['court:id,name,location', 'user:id,name', 'cancellation'])
            ->when(in_array($user->role, ['user', 'customer'], true), fn ($query) => $query->where('user_id', $user->id))
            ->when($user->role === 'owner', fn ($query) => $query->whereHas('court', fn ($courtQuery) => $courtQuery->where('owner_id', $user->id)))
            ->find($id);
    }

    public function overlappingBookings(Court $court, string $date, string $startTime, string $endTime): Collection
    {
        return Booking::query()
            ->where('court_id', $court->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'paid'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->lockForUpdate()
            ->get();
    }

    public function findById(int $id): ?Booking
    {
        return Booking::query()
            ->with(['court:id,name,location,owner_id', 'user:id,name,email', 'cancellation'])
            ->find($id);
    }

    public function update(Booking $booking, array $attributes): Booking
    {
        $booking->update($attributes);

        return $booking->fresh(['court:id,name,location', 'user:id,name', 'cancellation']);
    }
}
