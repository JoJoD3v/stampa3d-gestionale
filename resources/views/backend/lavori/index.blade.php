@extends('layouts.backend')

@section('title', 'Gestione Lavori')
@section('page-title', 'Gestione Lavori')

@section('content')

<div class="page-header">
    <h1>Lavori</h1>
    <a href="{{ route('backend.lavori.create') }}" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nuovo Lavoro
    </a>
</div>

@if($lavori->isEmpty())
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="7" width="20" height="14" rx="2"/>
            <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
        </svg>
        <p>Nessun lavoro trovato.<br>
            <a href="{{ route('backend.lavori.create') }}" style="color:#023059;font-weight:700;">Crea il primo lavoro</a>
        </p>
    </div>
@else
    <div class="card" style="padding:0;overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Lavoro</th>
                    <th>Cliente</th>
                    <th>Progetti</th>
                    <th>Stampante</th>
                    <th>Preventivo</th>
                    <th>Scadenza</th>
                    <th>Stato</th>
                    <th style="width:140px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($lavori as $lavoro)
                @php
                    $oggi = now()->startOfDay();
                    $scadenza = $lavoro->scadenza;
                    $scadenzaClass = '';
                    if ($scadenza) {
                        $diff = $oggi->diffInDays($scadenza, false);
                        if ($diff < 0) $scadenzaClass = 'scadenza-past';
                        elseif ($diff <= 3) $scadenzaClass = 'scadenza-soon';
                    }
                @endphp
                <tr data-href="{{ route('backend.lavori.show', $lavoro) }}">
                    <td>
                        <a href="{{ route('backend.lavori.show', $lavoro) }}" class="table-name-link">
                            {{ $lavoro->numero }}
                        </a>
                    </td>
                    <td>{{ $lavoro->customer->full_name }}</td>
                    <td>
                        <span style="font-size:.85rem;color:#374151;">
                            {{ $lavoro->projects->count() }}
                            {{ $lavoro->projects->count() === 1 ? 'progetto' : 'progetti' }}
                        </span>
                    </td>
                    <td>
                        @if($lavoro->printer)
                            <span class="printer-tag">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 6 2 18 2 18 9"/>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                    <rect x="6" y="14" width="12" height="8"/>
                                </svg>
                                {{ $lavoro->printer->name }}
                            </span>
                        @else
                            <a href="{{ route('backend.lavori.show', $lavoro) }}#stampante" class="btn btn-secondary btn-sm" style="font-size:.75rem;padding:.2rem .55rem;" title="Assegna stampante">
                                + Assegna
                            </a>
                        @endif
                    </td>
                    <td>
                        @if($lavoro->preventivo !== null)
                            <strong>€ {{ number_format($lavoro->preventivo, 2, ',', '.') }}</strong>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </td>
                    <td>
                        @if($lavoro->scadenza)
                            <span class="scadenza-badge {{ $scadenzaClass }}">{{ $lavoro->scadenza_formatted }}</span>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusOrder  = array_keys(\App\Models\Lavoro::STATUS_LABELS);
                            $currentIdx   = array_search($lavoro->status, $statusOrder);
                            $prevStatus   = $currentIdx > 0 ? $statusOrder[$currentIdx - 1] : null;
                            $nextStatus   = $currentIdx < count($statusOrder) - 1 ? $statusOrder[$currentIdx + 1] : null;
                        @endphp
                        <span class="status-lavoro status-{{ $lavoro->status }}">
                            {{ \App\Models\Lavoro::STATUS_LABELS[$lavoro->status] }}
                        </span>
                        @if($prevStatus || $nextStatus)
                        <div style="display:flex;gap:.3rem;flex-wrap:wrap;margin-top:.4rem;" onclick="event.stopPropagation()">
                            @if($prevStatus)
                            <form method="POST" action="{{ route('backend.lavori.update-status', $lavoro) }}" style="margin:0">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $prevStatus }}">
                                <button type="submit" class="btn-status-next status-next-{{ $prevStatus }}">&#8592; Passa a {{ \App\Models\Lavoro::STATUS_LABELS[$prevStatus] }}</button>
                            </form>
                            @endif
                            @if($nextStatus)
                            <form method="POST" action="{{ route('backend.lavori.update-status', $lavoro) }}" style="margin:0">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $nextStatus }}">
                                <button type="submit" class="btn-status-next status-next-{{ $nextStatus }}">Passa a {{ \App\Models\Lavoro::STATUS_LABELS[$nextStatus] }} &#8594;</button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:.35rem;justify-content:flex-end;">
                            <a href="{{ route('backend.lavori.show', $lavoro) }}" class="btn btn-secondary btn-sm" title="Dettaglio">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                            <a href="{{ route('backend.lavori.edit', $lavoro) }}" class="btn btn-secondary btn-sm" title="Modifica">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('backend.lavori.destroy', $lavoro) }}"
                                  onsubmit="return confirm('Eliminare il lavoro {{ $lavoro->numero }}?')"
                                  style="margin:0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Elimina">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6"/><path d="M14 11v6"/>
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
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
        {{ $lavori->links() }}
    </div>
@endif

@endsection
