<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Ticket;
use Illuminate\View\View;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Subcategory;
use App\Models\TicketStatus;

class TicketController extends Controller
{
    private const STATUS_TRANSITIONS = [
        'Open' => ['In Progress'],
        'In Progress' => ['Pending', 'Resolved'],
        'Pending' => ['In Progress'],
        'Resolved' => ['Closed', 'Reopened'],
        'Reopened' => ['In Progress'],
        'Closed' => [],
    ];

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

        $categories = Category::all();
        $priorities = Priority::all();
        $subcategories = Subcategory::where('category_id', $ticket->category_id)->get();

        $availableStatuses = $this->availableStatusesFor($ticket);

        return view('tickets.show', compact(
            'ticket',
            'categories',
            'priorities',
            'subcategories',
            'availableStatuses'
        ));
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

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority_id' => 'required|exists:priorities,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'status_id' => 'nullable|exists:ticket_status,id',
        ]);

        if (isset($validated['status_id'])) {
            $newStatus = TicketStatus::findOrFail($validated['status_id']);
            $currentStatus = $ticket->status()->firstOrFail();

            if (
                $newStatus->id !== $currentStatus->id
                && !in_array($newStatus->name, self::STATUS_TRANSITIONS[$currentStatus->name] ?? [], true)
            ) {
                throw ValidationException::withMessages([
                    'status_id' => "The ticket cannot move from {$currentStatus->name} to {$newStatus->name}.",
                ]);
            }

            $validated = $this->applyStatusDates($ticket, $currentStatus, $newStatus, $validated);
        }

        $ticket->update($validated);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status_id' => 'required|exists:ticket_status,id',
        ]);

        $currentStatus = $ticket->status()->firstOrFail();
        $newStatus = TicketStatus::findOrFail($validated['status_id']);

        if (
            $newStatus->id !== $currentStatus->id
            && !in_array($newStatus->name, self::STATUS_TRANSITIONS[$currentStatus->name] ?? [], true)
        ) {
            throw ValidationException::withMessages([
                'status_id' => "The ticket cannot move from {$currentStatus->name} to {$newStatus->name}.",
            ]);
        }

        $ticket->update($this->applyStatusDates($ticket, $currentStatus, $newStatus, []));

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket status updated successfully.');
    }

    private function availableStatusesFor(Ticket $ticket)
    {
        $allowedNames = self::STATUS_TRANSITIONS[$ticket->status->name] ?? [];

        return TicketStatus::whereIn('name', $allowedNames)
            ->orderBy('sort_order')
            ->get();
    }

    private function applyStatusDates(
        Ticket $ticket,
        TicketStatus $currentStatus,
        TicketStatus $newStatus,
        array $validated
    ): array {
        if ($currentStatus->id === $newStatus->id) {
            return $validated;
        }

        $validated['status_id'] = $newStatus->id;

        if ($newStatus->name === 'Resolved') {
            $validated['resolved_at'] = now();
            $validated['closed_at'] = null;
        } elseif ($newStatus->name === 'Reopened') {
            $validated['resolved_at'] = null;
            $validated['closed_at'] = null;
        } elseif ($newStatus->name === 'Closed') {
            $validated['closed_at'] = now();
        } else {
            $validated['resolved_at'] = null;
            $validated['closed_at'] = null;
        }

        return $validated;
    }
}
