@extends('layouts.backend')

@section('title', 'Modifica Utente')
@section('page-title', 'Modifica Utente')

@section('content')

<div class="page-header">
    <h1>Modifica: {{ $user->name }} {{ $user->surname }}</h1>
    <a href="{{ route('backend.users.index') }}" class="btn btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Torna alla Lista
    </a>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-header">
        <h2>Dati Utente</h2>
        <span style="font-size:0.78rem;color:#9ca3af;">ID: #{{ $user->id }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.users.update', $user) }}" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label" for="name">Nome <span style="color:#BF1111">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="surname">Cognome <span style="color:#BF1111">*</span></label>
                    <input
                        type="text"
                        id="surname"
                        name="surname"
                        class="form-control @error('surname') is-invalid @enderror"
                        value="{{ old('surname', $user->surname) }}"
                        required
                    >
                    @error('surname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label class="form-label" for="email">Email <span style="color:#BF1111">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password section --}}
                <div class="form-group form-grid-full">
                    <hr class="divider" style="margin: 0.25rem 0 1rem;">
                    <p style="font-size:0.82rem;color:#6b7280;margin-bottom:0.75rem;">
                        Lascia i campi password vuoti per non modificarla.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Nuova Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimo 8 caratteri"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Conferma Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ripeti la nuova password"
                    >
                </div>

                <div class="form-group form-grid-full">
                    <hr class="divider" style="margin: 0.5rem 0 1rem;">
                    <label class="form-check">
                        <input type="checkbox" name="is_admin" value="1"
                            {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                            {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                        <span>
                            <strong>Amministratore</strong>
                            <span style="color:#6b7280; font-size:0.82rem; margin-left:0.35rem;">— accesso completo al gestionale</span>
                        </span>
                    </label>
                    @if(auth()->id() === $user->id)
                        <p style="font-size:0.78rem;color:#9ca3af;margin-top:0.35rem;">Non puoi modificare il tuo stesso ruolo.</p>
                    @endif
                </div>

            </div>

            <hr class="divider">

            <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                <a href="{{ route('backend.users.index') }}" class="btn btn-secondary">Annulla</a>
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Salva Modifiche
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
