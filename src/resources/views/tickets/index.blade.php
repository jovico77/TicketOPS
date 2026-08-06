<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TicketOPS - Tickets</title>
</head>
<body>

    <h1>Ticket List</h1>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Technician</th>
                <th>Created By</th>
            </tr>
        </thead>

        <tbody>

        @foreach($tickets as $ticket)

            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->status->name }}</td>
                <td>{{ $ticket->priority->name }}</td>
                <td>{{ $ticket->category->name }}</td>
                <td>{{ $ticket->subcategory->name }}</td>
                <td>{{ $ticket->technician->name ?? 'Unassigned' }}</td>
                <td>{{ $ticket->creator->name ?? 'Unknown' }}</td>
            </tr>

        @endforeach

        </tbody>

    </table>

</body>
</html>