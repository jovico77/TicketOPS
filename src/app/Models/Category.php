<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
])]

class Category extends Model
{
    // Una categoría puede tener muchas subcategorías
        public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    // Una categoría puede tener muchos tickets
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
