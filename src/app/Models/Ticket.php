<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'ticket_number',
    'title',
    'description',
    'created_by',
    'assigned_to',
    'status_id',
    'priority_id',
    'category_id',
    'subcategory_id',
    'resolution',
    'resolution_type_id',
    'resolved_at',
    'closed_at',
])]
class Ticket extends Model
{
    use HasUuids, SoftDeletes;

    // La clave primaria es un UUID
    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Usuario que creó el ticket.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Técnico asignado al ticket.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Estado del ticket.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class);
    }

    /**
     * Prioridad del ticket.
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    /**
     * Categoría del ticket.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Subcategoría del ticket.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Tipo de resolución.
     */
    public function resolutionType(): BelongsTo
    {
        return $this->belongsTo(ResolutionType::class, 'resolution_type_id');
    }

    /**
     * Comentarios del ticket.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}