<?php

namespace App\Services;

use App\Models\Court;
use App\Models\User;
use App\Repositories\CourtRepository;
use App\Services\FacilityService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CourtService
{
    public const DAYS = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    public function __construct(
        protected CourtRepository $courts,
        protected FacilityService $facilityService,
    ) {
    }

    public function listPublic(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return $this->courts->paginatePublic($perPage, $filters);
    }

    public function listForOwner(User $user, int $perPage = 10): LengthAwarePaginator
    {
        $this->ensureCanManage($user);

        return $this->courts->paginateForOwner($user, $perPage);
    }

    public function findPublicOrFail(int $id): Court
    {
        return $this->courts->findPublic($id) ?? throw new ModelNotFoundException();
    }

    public function findOwnedOrFail(int $id, User $user): Court
    {
        $this->ensureCanManage($user);

        return $this->courts->findOwnedByUser($id, $user) ?? throw new ModelNotFoundException();
    }

    public function create(array $attributes, User $user): Court
    {
        $this->ensureCanManage($user);

        return DB::transaction(function () use ($attributes, $user) {
            $court = $this->courts->create([
                'owner_id' => $user->role === 'owner' ? $user->id : ($attributes['owner_id'] ?? $user->id),
                'name' => $attributes['name'],
                'slug' => $this->makeUniqueSlug($attributes['name']),
                'description' => $attributes['description'] ?? null,
                'location' => $attributes['location'],
                'price_per_hour' => $attributes['price_per_hour'],
                'cover_image' => $attributes['cover_image'] ?? null,
                'rating' => 0,
                'status' => ($attributes['status'] ?? null) === 'inactive' ? 'inactive' : 'active',
                'is_active' => (bool) ($attributes['is_active'] ?? true),
            ]);

            $court->schedules()->createMany($this->normalizeSchedules($attributes['schedules'] ?? []));
            $this->facilityService->syncCourtFacilities($court, $attributes['facility_ids'] ?? []);

            return $court->fresh(['owner:id,name', 'schedules', 'facilities']);
        });
    }

    public function update(Court $court, array $attributes, User $user): Court
    {
        $this->ensureOwnership($court, $user);

        return DB::transaction(function () use ($court, $attributes) {
            $court = $this->courts->update($court, [
                'name' => $attributes['name'],
                'slug' => $this->makeUniqueSlug($attributes['name'], $court->id),
                'description' => $attributes['description'] ?? null,
                'location' => $attributes['location'],
                'price_per_hour' => $attributes['price_per_hour'],
                'cover_image' => $attributes['cover_image'] ?? null,
                'status' => ($attributes['status'] ?? null) === 'inactive' ? 'inactive' : 'active',
                'is_active' => (bool) ($attributes['is_active'] ?? false),
            ]);

            $court->schedules()->delete();
            $court->schedules()->createMany($this->normalizeSchedules($attributes['schedules'] ?? []));
            $this->facilityService->syncCourtFacilities($court, $attributes['facility_ids'] ?? []);

            return $court->fresh(['owner:id,name', 'schedules', 'facilities']);
        });
    }

    public function delete(Court $court, User $user): void
    {
        $this->ensureOwnership($court, $user);

        DB::transaction(fn () => $this->courts->delete($court));
    }

    /**
     * @return array<int, string>
     */
    public function days(): array
    {
        return self::DAYS;
    }

    protected function normalizeSchedules(array $schedules): array
    {
        return collect($schedules)
            ->map(function (array $schedule, int $index) {
                $isOpen = (bool) ($schedule['is_open'] ?? false);

                return [
                    'day_of_week' => $schedule['day_of_week'] ?? $index + 1,
                    'open_time' => $isOpen ? $schedule['open_time'] : null,
                    'close_time' => $isOpen ? $schedule['close_time'] : null,
                    'is_open' => $isOpen,
                ];
            })
            ->sortBy('day_of_week')
            ->values()
            ->all();
    }

    protected function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (Court::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    protected function ensureCanManage(User $user): void
    {
        if (! in_array($user->role, ['admin', 'owner'], true)) {
            throw new HttpException(403, 'You are not allowed to manage courts.');
        }
    }

    protected function ensureOwnership(Court $court, User $user): void
    {
        $this->ensureCanManage($user);

        if ($user->role === 'owner' && $court->owner_id !== $user->id) {
            throw new HttpException(403, 'You are not allowed to manage this court.');
        }
    }
}
