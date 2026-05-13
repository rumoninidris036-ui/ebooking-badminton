<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'type',
    'title',
    'message',
    'channel',
    'is_read',
    'created_at',
])]
class UserNotification extends Model
{
    public $timestamps = false;

    protected $table = 'notifications';

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'is_read' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
