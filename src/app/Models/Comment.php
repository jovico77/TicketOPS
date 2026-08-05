<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'ticket_id',
    'user_id',
    'message',
    'is_private',
])]
class Comment extends Model
{
    /**
     * Ticket al que pertenece el comentario.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Usuario que escribió el comentario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}