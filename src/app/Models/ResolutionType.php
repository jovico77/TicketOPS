<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]

class ResolutionType extends Model
{
    // Una resolución puede tener muchos tickets
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
