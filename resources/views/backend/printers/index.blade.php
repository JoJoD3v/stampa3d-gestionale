@extends('layouts.backend')

@section('title', 'Gestione Stampanti')
@section('page-title', 'Gestione Stampanti')

@section('content')

<div class="page-header">
    <h1>Stampanti 3D</h1>
    <a href="{{ route('backend.printers.create') }}" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nuova Stampante
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Lista Stampanti <span style="font-weight:400;color:#6b7280;font-size:0.85rem;">({{ $printers->total() }} totali)</span></h2>
    </div>

    @if($printers->isEmpty())
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <polyline points="6 9 6 2 18 2 18 9"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect x="6" y="14" width="12" height="8"/>
            </svg>
            <p>Nessuna stampante trovata.<br>
                <a href="{{ route('backend.printers.create') }}" style="color:#023059;font-weight:700;">Aggiungi la prima stampante</a>
            </p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome Stampante</th>
                        <th>Modello</th>
                        <th>Status</th>
                        <th>Aggiunta il</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($printers as $printer)
                    <tr data-href="{{ route('backend.printers.edit', $printer) }}">
                        <td style="color:#9ca3af;font-size:0.8rem;">{{ $printer->id }}</td>
                        <td><strong>{{ $printer->name }}</strong></td>
                        <td style="color:#4b5563;">{{ $printer->model }}</td>
                        <td>
                            <span class="status-badge {{ $printer->status_color }}">
                                {{ $printer->status_label }}
                            </span>
                        </td>
                        <td style="color:#6b7280;font-size:0.82rem;">{{ $printer->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('backend.printers.edit', $printer) }}"
                                   class="btn btn-secondary btn-sm" title="Modifica">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Modifica
                                </a>

                                <form method="POST" action="{{ route('backend.printers.destroy', $printer) }}"
                                      onsubmit="return confirm('Eliminare la stampante {{ addslashes($printer->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6"/><path d="M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                        </svg>
                                        Elimina
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($printers->hasPages())
        <div style="padding: 1rem 1.3rem; border-top: 1px solid #f0f3f8;">
            {{ $printers->links('vendor.pagination.custom') }}
        </div>
        @endif
    @endif
</div>

@endsection
