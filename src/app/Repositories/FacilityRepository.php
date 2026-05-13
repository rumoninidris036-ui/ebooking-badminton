<?php

namespace App\Repositories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Collection;

class FacilityRepository
{
    public function all(): Collection
    {
        return Facility::query()->orderBy('name')->get();
    }

    public function create(array $attributes): Facility
    {
        return Facility::query()->create($attributes);
    }

    public function findById(int $id): ?Facility
    {
        return Facility::query()->find($id);
    }

    public function update(Facility $facility, array $attributes): Facility
    {
        $facility->update($attributes);

        return $facility->fresh();
    }

    public function delete(Facility $facility): void
    {
        $facility->delete();
    }
}
