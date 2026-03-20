<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi — Gestionale Stampe 3D</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="login-page">
    <div class="login-card">

        {{-- Logo / Brand --}}
        <div class="login-logo">
            <img src="{{ asset('favicon/favicon-96x96.png') }}" width="56" height="56" alt="Logo" style="border-radius:12px;">
            <h1>Gestionale Stampe 3D</h1>
            <p>Accedi al pannello di gestione</p>
        </div>

        {{-- Error alert --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" autocomplete="off">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="email@esempio.it"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••"
                    required
                >
            </div>

            <div class="form-group" style="margin-top: 0.25rem;">
                <label class="form-check">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ricordami
                </label>
            </div>

            <button type="submit" class="btn-login">
                Accedi
            </button>
        </form>

    </div>
</div>
</body>
</html>
