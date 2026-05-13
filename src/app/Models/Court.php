<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'owner_id',
    'name',
    'slug',
    'description',
    'location',
    'price_per_hour',
    'cover_image',
    'rating',
    'status',
    'is_active',
])]
class Court extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price_per_hour' => 'decimal:2',
            'rating' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourtSchedule::class)->orderBy('day_of_week');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class)->latest('booking_date');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'court_facility')->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class)->latest('similarity_score');
    }
}
