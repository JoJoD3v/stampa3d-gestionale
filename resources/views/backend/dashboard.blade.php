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
                <polyline points="6 9 6 2 18 2 18 9"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect x="6" y="14" width="12" height="8"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ \App\Models\Printer::count() }}</div>
            <div class="stat-label">Stampanti Registrate</div>
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
            <div class="stat-number">{{ $clientiCount }}</div>
            <div class="stat-label">Clienti Registrati</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $lavoriAttivi }}</div>
            <div class="stat-label">Lavori Attivi</div>
        </div>
    </div>

</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">

    {{-- In Stampa --}}
    <div class="card">
        <div class="card-header">
            <h2>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:.35rem;">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                In Stampa
                @if($printersInUso->isNotEmpty())
                    <span style="background:#d97706;color:#fff;font-size:.7rem;padding:.15rem .45rem;border-radius:999px;margin-left:.4rem;vertical-align:middle;">{{ $printersInUso->count() }}</span>
                @endif
            </h2>
        </div>
        <div class="card-body" style="padding:0;">
            @if($printersInUso->isEmpty())
                <div style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:.9rem;">Nessuna stampante in uso.</div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Stampante</th>
                            <th>Lavoro</th>
                            <th>Cliente</th>
                            <th>Fine stimata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($printersInUso as $printer)
                        @php
                            $lav = $printer->lavoroAttivo;
                            $fineStampa = $lav?->fine_stampa;
                        @endphp
                        <tr>
                            <td>
                                <strong style="color:#023059;">{{ $printer->name }}</strong>
                                @if($printer->model)
                                    <div style="font-size:.77rem;color:#9ca3af;">{{ $printer->model }}</div>
                                @endif
                            </td>
                            <td>
                                @if($lav)
                                    <a href="{{ route('backend.lavori.show', $lav) }}" class="table-name-link">{{ $lav->numero }}</a>
                                @else
                                    <span style="color:#9ca3af">—</span>
                                @endif
                            </td>
                            <td style="font-size:.88rem;">{{ $lav?->customer?->full_name ?? '—' }}</td>
                            <td style="font-size:.88rem;">
                                @if($fineStampa)
                                    <strong style="color:#023059;">{{ $fineStampa->format('d/m H:i') }}</strong>
                                @elseif($lav?->avvio_stampa_at)
                                    <span style="color:#9ca3af;">—</span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Disponibili --}}
    <div class="card">
        <div class="card-header">
            <h2>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:.35rem;">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                Stampanti Disponibili
                @if($printersSpente->isNotEmpty())
                    <span style="background:#059669;color:#fff;font-size:.7rem;padding:.15rem .45rem;border-radius:999px;margin-left:.4rem;vertical-align:middle;">{{ $printersSpente->count() }}</span>
                @endif
            </h2>
        </div>
        <div class="card-body" style="padding:0;">
            @if($printersSpente->isEmpty())
                <div style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:.9rem;">Nessuna stampante disponibile.</div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Stampante</th>
                            <th>Modello</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($printersSpente as $printer)
                        <tr>
                            <td>
                                <strong style="color:#023059;">{{ $printer->name }}</strong>
                            </td>
                            <td style="font-size:.88rem;color:#6b7280;">{{ $printer->model ?? '—' }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('backend.lavori.create') }}" class="btn btn-secondary btn-sm" style="font-size:.75rem;">Nuovo lavoro</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>

@endsection
