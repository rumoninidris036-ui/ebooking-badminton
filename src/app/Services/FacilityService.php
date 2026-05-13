<?php

namespace App\Services;

use App\Models\Court;
use App\Models\Facility;
use App\Repositories\FacilityRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FacilityService
{
    public function __construct(
        protected FacilityRepository $facilities,
    ) {
    }

    public function listAll(): Collection
    {
        return $this->facilities->all();
    }

    public function create(array $attributes, mixed $user): Facility
    {
        $this->ensureCanManage($user?->role);

        return $this->facilities->create($attributes);
    }

    public function update(int $id, array $attributes, mixed $user): Facility
    {
        $this->ensureCanManage($user?->role);
        $facility = $this->facilities->findById($id) ?? throw new HttpException(404, 'Facility not found.');

        return $this->facilities->update($facility, $attributes);
    }

    public function delete(int $id, mixed $user): void
    {
        $this->ensureCanManage($user?->role);
        $facility = $this->facilities->findById($id) ?? throw new HttpException(404, 'Facility not found.');
        $this->facilities->delete($facility);
    }

    public function syncCourtFacilities(Court $court, array $facilityIds): void
    {
        $court->facilities()->sync($facilityIds);
    }

    protected function ensureCanManage(?string $role): void
    {
        if (! in_array($role, ['admin', 'owner'], true)) {
            throw new HttpException(403, 'You are not allowed to manage facilities.');
        }
    }
}
