<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'name',
    'icon',
])]
class Facility extends Model
{
    public function courts(): BelongsToMany
    {
        return $this->belongsToMany(Court::class, 'court_facility')->withTimestamps();
    }
}
