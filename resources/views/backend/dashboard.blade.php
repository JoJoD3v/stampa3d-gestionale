@extends('layouts.backend')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ \App\Models\User::count() }}</div>
            <div class="stat-label">Utenti Totali</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                <path d="M2 17l10 5 10-5"/>
                <path d="M2 12l10 5 10-5"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">0</div>
            <div class="stat-label">Stampe in Corso</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">0</div>
            <div class="stat-label">Clienti Attivi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">0</div>
            <div class="stat-label">Ordini del Mese</div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header">
        <h2>Benvenuto nel Gestionale</h2>
    </div>
    <div class="card-body">
        <p style="color: #4b5563; line-height: 1.65;">
            Benvenuto, <strong>{{ auth()->user()->name }} {{ auth()->user()->surname }}</strong>!<br>
            Utilizza il menu laterale per navigare tra le sezioni del gestionale.
            Puoi gestire gli utenti, i clienti e le stampe 3D dalla barra di navigazione a sinistra.
        </p>
        <hr class="divider">
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ route('backend.users.index') }}" class="btn btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                Gestisci Utenti
            </a>
        </div>
    </div>
</div>

@endsection
