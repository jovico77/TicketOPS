<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = Ticket::with([
            'creator',
            'technician',
            'status',
            'priority',
            'category',
            'subcategory',
        ])
        ->when($request->status, function ($query) use ($request) {
            $query->whereHas('status', function ($statusQuery) use ($request) {
                $statusQuery->where('name', $request->status);
            });
        })
        ->when($request->priority, function ($query) use ($request) {
            $query->whereHas('priority', function ($priorityQuery) use ($request) {
                $priorityQuery->where('name', $request->priority);
            });
        })
        ->when($request->category, function ($query) use ($request) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery->where('name', $request->category);
            });
        })
        ->when($request->search, function ($query) use ($request) {
                $query->where(function ($searchQuery) use ($request) {
                $searchQuery
                ->where('ticket_number', 'ILIKE', '%' . $request->search . '%')
                ->orWhere('title', 'ILIKE', '%' . $request->search . '%')
                ->orWhere('description', 'ILIKE', '%' . $request->search . '%');
            });
        })
        ->paginate(2)
        ->withQueryString();

        return view('tickets.index', compact('tickets'));
        return view('tickets.create');
    }
}
