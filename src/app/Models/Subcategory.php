<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'name',
    'description',
])]
class Subcategory extends Model
{
    /**
     * Una subcategoría pertenece a una categoría.
     */
        public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Una subcategoría puede tener muchos tickets.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
