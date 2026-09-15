<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'TicketOPS')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
</head>

<body>

<nav class="navbar">

    <div class="container-fluid">
        <a href="{{ route('tickets.index') }}" class="navbar-brand">TicketOPS</a>
        <div class="d-flex align-items-center gap-3 profile"> <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

</nav>

<div class="container content">

    @yield('content')

</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/alert.js') }}"></script>
</body>

</html>