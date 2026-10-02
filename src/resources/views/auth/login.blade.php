<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <title>Sign in | TicketOPS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="login-panel" aria-labelledby="login-title">
            <a class="login-brand" href="{{ route('login') }}" aria-label="TicketOPS">
                <span class="login-brand-mark" aria-hidden="true">T</span>
                <span>Ticket<span>OPS</span></span>
            </a>

            <div class="login-form-wrap">
                <p class="login-form-eyebrow">WELCOME BACK</p>
                <h1 id="login-title">Sign in to your account</h1>
                <p class="login-form-description">Enter your details to access your support workspace.</p>

                @if ($errors->any())
                    <div class="login-error" role="alert">
                        <span class="login-error-icon" aria-hidden="true">!</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <div class="login-field">
                        <label for="email">Email address</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            placeholder="you@example.com"
                            required
                            autofocus
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
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-submit">
                        Sign in
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </form>

                <div class="login-divider"><span>or continue with</span></div>

                <div class="login-social-buttons" aria-label="Social sign-in options">
                    <button class="login-social-button" type="button" title="Google sign-in is not configured yet" aria-label="Google sign-in (not configured)">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M21.6 12.23c0-.72-.06-1.42-.18-2.09H12v3.95h5.38a4.6 4.6 0 0 1-2 3.02v2.47h3.25c1.9-1.75 2.97-4.33 2.97-7.35Z"/><path fill="#34A853" d="M12 22c2.7 0 4.96-.9 6.62-2.42l-3.25-2.47c-.9.6-2.03.96-3.37.96-2.6 0-4.8-1.76-5.59-4.12H3.05v2.55A10 10 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.41 13.95a6.02 6.02 0 0 1 0-3.9V7.5H3.05a10 10 0 0 0 0 9l3.36-2.55Z"/><path fill="#EA4335" d="M12 5.93c1.47 0 2.79.5 3.82 1.5l2.87-2.87C16.95 2.94 14.7 2 12 2a10 10 0 0 0-8.95 5.5l3.36 2.55C7.2 7.69 9.4 5.93 12 5.93Z"/></svg>
                    </button>
                    <button class="login-social-button" type="button" title="Apple sign-in is not configured yet" aria-label="Apple sign-in (not configured)">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M16.37 12.03c.02 2.1 1.84 2.8 1.86 2.81-.02.05-.29 1-.96 1.98-.58.85-1.18 1.7-2.12 1.72-.93.02-1.23-.55-2.3-.55s-1.4.53-2.28.57c-.91.04-1.6-.92-2.18-1.76-1.19-1.72-2.1-4.87-.88-6.99a3.38 3.38 0 0 1 2.85-1.73c.89-.02 1.73.6 2.28.6.55 0 1.58-.75 2.66-.64.45.02 1.72.18 2.54 1.36-.07.05-1.52.89-1.5 2.63ZM14.62 5.9c.48-.58.8-1.39.71-2.2-.69.03-1.52.46-2.01 1.04-.44.51-.83 1.34-.72 2.13.77.06 1.55-.39 2.02-.97Z"/></svg>
                    </button>
                    <button class="login-social-button" type="button" title="GitHub sign-in" aria-label="GitHub sign-in (not configured)">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.56v-2.02c-3.2.7-3.88-1.36-3.88-1.36-.53-1.33-1.28-1.69-1.28-1.69-1.04-.71.08-.7.08-.7 1.15.08 1.75 1.18 1.75 1.18 1.03 1.75 2.7 1.25 3.36.96.1-.75.4-1.25.73-1.54-2.55-.29-5.23-1.28-5.23-5.69 0-1.26.45-2.29 1.18-3.1-.12-.29-.51-1.47.11-3.06 0 0 .96-.31 3.15 1.18A10.9 10.9 0 0 1 12 6.15c.97 0 1.94.13 2.85.38 2.19-1.49 3.15-1.18 3.15-1.18.62 1.59.23 2.77.11 3.06.73.81 1.18 1.84 1.18 3.1 0 4.42-2.69 5.4-5.25 5.68.41.36.78 1.08.78 2.18v3.23c0 .31.21.68.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"/>
                        </svg>
                    </button>
                </div>
                <p class="login-social-note">Social sign-in options are coming soon.</p>
            </div>

            <footer class="login-footer">
                <p class="login-register-link">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create an account</a>
                </p>
                <p class="login-copyright">&copy; {{ date('Y') }} TicketOPS</p>
            </footer>
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
