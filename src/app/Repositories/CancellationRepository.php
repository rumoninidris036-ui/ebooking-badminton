<?php

namespace App\Repositories;

use App\Models\Cancellation;

class CancellationRepository
{
    public function create(array $attributes): Cancellation
    {
        return Cancellation::query()->create($attributes);
    }
}
