<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\NotificationRepository;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $notifications,
    ) {
    }

    public function sendInApp(User $user, string $type, string $title, string $message): void
    {
        $this->notifications->create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'channel' => 'in_app',
            'is_read' => false,
            'created_at' => now(),
        ]);
    }
}
