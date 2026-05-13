<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'court_id',
    'day_of_week',
    'open_time',
    'close_time',
    'is_open',
])]
class CourtSchedule extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_open' => 'boolean',
        ];
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }
}
