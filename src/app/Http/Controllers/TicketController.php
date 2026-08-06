<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $tickets = Ticket::with([
            'creator',
            'technician',
            'status',
            'priority',
            'category',
            'subcategory',
        ])->get();

        return view('tickets.index', compact('tickets'));
    }
}
