@extends('layouts.backend')

@section('title', 'Gestione Vendite')
@section('page-title', 'Gestione Vendite')

@section('content')

<div class="page-header">
    <h1>Vendite dirette</h1>
    <a href="{{ route('backend.vendite.create') }}" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nuova Vendita
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Ricerca --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-body" style="padding:.85rem 1rem;">
        <form method="GET" action="{{ route('backend.vendite.index') }}" style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cerca per progetto…"
                   style="max-width:320px;">
            <button type="submit" class="btn btn-primary btn-sm">Cerca</button>
            @if(request('search'))
                <a href="{{ route('backend.vendite.index') }}" class="btn btn-secondary btn-sm">Azzera</a>
            @endif
        </form>
    </div>
</div>

{{-- Stat cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">€ {{ number_format($totale, 2, ',', '.') }}</div>
            <div class="stat-label">Totale vendite</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $vendite->total() }}</div>
            <div class="stat-label">Totale vendite registrate</div>
        </div>
    </div>
</div>

{{-- Tabella --}}
@if($vendite->isEmpty())
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <p>Nessuna vendita trovata.<br>
            <a href="{{ route('backend.vendite.create') }}" style="color:#023059;font-weight:700;">Registra la prima vendita</a>
        </p>
    </div>
@else
    <div class="card" style="padding:0;overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Progetto</th>
                    <th style="text-align:right;">Importo</th>
                    <th>Data vendita</th>
                    <th>Note</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendite as $vendita)
                <tr>
                    <td>
                        <a href="{{ route('backend.vendite.edit', $vendita) }}" class="table-name-link">
                            {{ $vendita->project->name }}
                        </a>
                    </td>
                    <td style="text-align:right;font-weight:700;color:#023059;">
                        € {{ number_format($vendita->importo, 2, ',', '.') }}
                    </td>
                    <td style="color:#6b7280;font-size:.88rem;">
                        {{ $vendita->data_vendita->format('d/m/Y') }}
                    </td>
                    <td style="color:#6b7280;font-size:.88rem;max-width:240px;">
                        {{ $vendita->note ? \Illuminate\Support\Str::limit($vendita->note, 60) : '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.35rem;justify-content:flex-end;">
                            <a href="{{ route('backend.vendite.edit', $vendita) }}" class="btn btn-secondary btn-sm" title="Modifica">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('backend.vendite.destroy', $vendita) }}"
                                  onsubmit="return confirm('Eliminare questa vendita?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Elimina">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14H6L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                        <path d="M9 6V4h6v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        {{ $vendite->withQueryString()->links() }}
    </div>
@endif

@endsection
