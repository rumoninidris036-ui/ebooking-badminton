<?php

namespace App\Repositories;

use App\Models\UserNotification;

class NotificationRepository
{
    public function create(array $attributes): UserNotification
    {
        return UserNotification::query()->create($attributes);
    }
}
