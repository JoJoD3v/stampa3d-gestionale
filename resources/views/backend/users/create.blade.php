@extends('layouts.backend')

@section('title', 'Nuovo Utente')
@section('page-title', 'Nuovo Utente')

@section('content')

<div class="page-header">
    <h1>Crea Nuovo Utente</h1>
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
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.users.store') }}" autocomplete="off">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label" for="name">Nome <span style="color:#BF1111">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Es. Mario"
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
                        value="{{ old('surname') }}"
                        placeholder="Es. Rossi"
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
                        value="{{ old('email') }}"
                        placeholder="mario.rossi@esempio.it"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password <span style="color:#BF1111">*</span></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimo 8 caratteri"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Conferma Password <span style="color:#BF1111">*</span></label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ripeti la password"
                        required
                    >
                </div>

                <div class="form-group form-grid-full">
                    <hr class="divider" style="margin: 0.5rem 0 1rem;">
                    <label class="form-check">
                        <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                        <span>
                            <strong>Amministratore</strong>
                            <span style="color:#6b7280; font-size:0.82rem; margin-left:0.35rem;">— accesso completo al gestionale</span>
                        </span>
                    </label>
                </div>

            </div>

            <hr class="divider">

            <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                <a href="{{ route('backend.users.index') }}" class="btn btn-secondary">Annulla</a>
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Crea Utente
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
