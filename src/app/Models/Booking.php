<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'booking_code',
    'user_id',
    'court_id',
    'schedule_id',
    'booking_date',
    'start_time',
    'end_time',
    'duration_hours',
    'price_per_hour',
    'total_price',
    'status',
    'notes',
])]
class Booking extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'duration_hours' => 'integer',
            'price_per_hour' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function cancellation(): HasOne
    {
        return $this->hasOne(Cancellation::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
