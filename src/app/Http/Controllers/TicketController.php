<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\View\View;
use App\Models\Category;
use App\Models\Priority;
use App\Models\TicketStatus;

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
        ->paginate(10)
        ->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $priorities = Priority::all();

        return view('tickets.create', compact('categories', 'priorities'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load([
            'creator',
            'technician',
            'status',
            'priority',
            'category',
            'subcategory',
            'resolutionType',
            'comments.user',
        ]);

        return view('tickets.show', compact('ticket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority_id' => 'required|exists:priorities,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
        ]);

        $status = TicketStatus::where('name', 'Open')->first();

        $lastTicket = Ticket::orderBy('created_at', 'desc')->first();

        if ($lastTicket) {
            $lastNumber = (int) substr($lastTicket->ticket_number, -6);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $ticketNumber = 'TCK-' . now()->year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'created_by' => auth()->id(),
            'status_id' => $status->id,
            'priority_id' => $validated['priority_id'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'] ?? null,
        ]);

            return redirect()
        ->route('tickets.index')
        ->with('success', 'Ticket created successfully.');
    }
}
