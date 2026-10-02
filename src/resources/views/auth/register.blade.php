<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <title>Create an account | TicketOPS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-panel" aria-labelledby="register-title">
            <a class="login-brand" href="{{ route('login') }}" aria-label="TicketOPS">
                <span class="login-brand-mark" aria-hidden="true">T</span>
                <span>Ticket<span>OPS</span></span>
            </a>

            <div class="login-form-wrap">
                <p class="login-form-eyebrow">GET STARTED</p>
                <h1 id="register-title">Create your account</h1>
                <p class="login-form-description">Register to submit and follow your support requests.</p>

                @if ($errors->any())
                    <div class="login-error" role="alert">
                        <span class="login-error-icon" aria-hidden="true">!</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}" class="login-form">
                    @csrf

                    <div class="login-field">
                        <label for="name">Full name</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            placeholder="Your name"
                            maxlength="100"
                            required
                            autofocus
                            @if ($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif
                        >
                        @error('name')
                            <span class="login-field-error" id="name-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="email">Email address</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="you@example.com"
                            maxlength="100"
                            required
                            @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                        >
                        @error('email')
                            <span class="login-field-error" id="email-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete="new-password"
                            placeholder="At least 8 characters"
                            required
                            @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                        >
                        @error('password')
                            <span class="login-field-error" id="password-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="password_confirmation">Confirm password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Repeat your password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-submit">
                        Create account
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </form>

                <p class="login-register-link">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign in</a>
                </p>
            </div>

            <p class="login-copyright">&copy; {{ date('Y') }} TicketOPS</p>
        </section>

        <aside class="login-intro" aria-label="TicketOPS support workspace">
            <div class="login-image-overlay">
                <p class="login-eyebrow">HELPDESK WORKSPACE</p>
                <h2>Every request,<br>moving forward.</h2>
                <p>Report issues, follow progress and keep support moving in one place.</p>
            </div>
        </aside>
    </main>
</body>
</html>
