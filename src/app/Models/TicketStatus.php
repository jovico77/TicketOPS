<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'color',
    'sort_order',
])]
class TicketStatus extends Model
{
    protected $table = 'ticket_status';
}