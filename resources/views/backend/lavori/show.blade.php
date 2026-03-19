@extends('layouts.backend')

@section('title', $lavoro->numero . ' — Dettaglio')
@section('page-title', 'Dettaglio Lavoro')

@section('content')

<div class="page-header">
    <h1>{{ $lavoro->numero }}</h1>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('backend.lavori.edit', $lavoro) }}" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Modifica
        </a>
        <a href="{{ route('backend.lavori.index') }}" class="btn btn-secondary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Lista
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:1.5rem;align-items:start;">

    {{-- LEFT: Info lavoro --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Cliente --}}
        <div class="card">
            <div class="card-header"><h2>Cliente</h2></div>
            <div class="card-body">
                <div style="font-size:1.05rem;font-weight:700;color:#023059;margin-bottom:.25rem;">
                    {{ $lavoro->customer->full_name }}
                </div>
                @if($lavoro->customer->email)
                    <div class="table-sub-item" style="margin-top:.4rem;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        {{ $lavoro->customer->email }}
                    </div>
                @endif
                @if($lavoro->customer->telefono)
                    <div class="table-sub-item" style="margin-top:.25rem;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.09 6.09l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        {{ $lavoro->customer->telefono }}
                    </div>
                @endif
                <div style="margin-top:.75rem;">
                    <a href="{{ route('backend.customers.show', $lavoro->customer) }}" class="btn btn-secondary btn-sm">
                        Vedi profilo cliente
                    </a>
                </div>
            </div>
        </div>

        {{-- Dettagli --}}
        <div class="card">
            <div class="card-header"><h2>Dettagli</h2></div>
            <div class="card-body">
                <dl style="display:grid;grid-template-columns:max-content 1fr;gap:.5rem 1.25rem;margin:0;">
                    <dt style="color:#6b7280;font-size:.85rem;">Stato</dt>
                    <dd style="margin:0;">
                        <span class="status-lavoro status-{{ $lavoro->status }}">
                            {{ \App\Models\Lavoro::STATUS_LABELS[$lavoro->status] }}
                        </span>
                    </dd>

                    <dt style="color:#6b7280;font-size:.85rem;">Preventivo</dt>
                    <dd style="margin:0;font-weight:700;color:#023059;">
                        @if($lavoro->preventivo !== null)
                            € {{ number_format($lavoro->preventivo, 2, ',', '.') }}
                        @else
                            <span style="color:#9ca3af;font-weight:400;">—</span>
                        @endif
                    </dd>

                    <dt style="color:#6b7280;font-size:.85rem;">Scadenza</dt>
                    <dd style="margin:0;">
                        @if($lavoro->scadenza)
                            @php
                                $diff = now()->startOfDay()->diffInDays($lavoro->scadenza, false);
                                $cls = $diff < 0 ? 'scadenza-past' : ($diff <= 3 ? 'scadenza-soon' : '');
                            @endphp
                            <span class="scadenza-badge {{ $cls }}">{{ $lavoro->scadenza_formatted }}</span>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </dd>

                    <dt style="color:#6b7280;font-size:.85rem;">Creato il</dt>
                    <dd style="margin:0;font-size:.88rem;color:#374151;">{{ $lavoro->created_at->format('d/m/Y H:i') }}</dd>
                </dl>

                @if($lavoro->note)
                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;">
                        <div style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:.4rem;">Note</div>
                        <p style="margin:0;font-size:.9rem;color:#374151;white-space:pre-line;">{{ $lavoro->note }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Stampante --}}
        <div class="card" id="stampante">
            <div class="card-header"><h2>Stampante</h2></div>
            <div class="card-body">
                @if($lavoro->printer_id)
                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#023059" stroke-width="2">
                            <polyline points="6 9 6 2 18 2 18 9"/>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                            <rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        <strong style="font-size:1.05rem;color:#023059;">{{ $lavoro->printer->name }}</strong>
                        <span class="printer-status status-busy" style="font-size:.75rem;">In Uso</span>
                    </div>
                    <dl style="display:grid;grid-template-columns:max-content 1fr;gap:.45rem 1.25rem;margin:0 0 1rem 0;">
                        <dt style="color:#6b7280;font-size:.85rem;">Avvio stampa</dt>
                        <dd style="margin:0;font-size:.88rem;color:#374151;">{{ $lavoro->avvio_stampa_at->format('d/m/Y H:i') }}</dd>
                        @if($lavoro->fine_stampa)
                            <dt style="color:#6b7280;font-size:.85rem;">Fine stimata</dt>
                            <dd style="margin:0;font-weight:700;color:#023059;">{{ $lavoro->fine_stampa->format('d/m/Y H:i') }}</dd>
                        @else
                            <dt style="color:#6b7280;font-size:.85rem;">Fine stimata</dt>
                            <dd style="margin:0;color:#9ca3af;font-size:.85rem;">Nessun tempo configurato nei progetti</dd>
                        @endif
                    </dl>
                    <form method="POST" action="{{ route('backend.lavori.release-printer', $lavoro) }}"
                          onsubmit="return confirm('Liberare la stampante da questo lavoro?')" style="margin:0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Libera stampante</button>
                    </form>
                @else
                    @if($printersDisponibili->isEmpty())
                        <p style="color:#9ca3af;font-size:.9rem;margin:0;">
                            Nessuna stampante disponibile.<br>
                            <a href="{{ route('backend.printers.index') }}" style="color:#023059;font-weight:600;">Gestisci le stampanti</a>
                        </p>
                    @else
                        <form method="POST" action="{{ route('backend.lavori.assign-printer', $lavoro) }}" style="margin:0">
                            @csrf
                            <div style="margin-bottom:.75rem;">
                                <label style="font-size:.85rem;color:#374151;display:block;margin-bottom:.35rem;font-weight:600;">Seleziona stampante</label>
                                <select name="printer_id" class="form-control" required style="width:100%;padding:.45rem .65rem;border:1px solid #d1d5db;border-radius:6px;font-size:.9rem;background:#fff;">
                                    <option value="">— scegli stampante —</option>
                                    @foreach($printersDisponibili as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}{{ $p->model ? ' ('.$p->model.')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 6 2 18 2 18 9"/>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                    <rect x="6" y="14" width="12" height="8"/>
                                </svg>
                                Avvia stampa
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

    </div>

    {{-- RIGHT: Progetti --}}
    <div class="card">
        <div class="card-header">
            <h2>Progetti ({{ $lavoro->projects->count() }})</h2>
        </div>
        <div class="card-body" style="padding:0;">

            {{-- Summary bar --}}
            @php
                $totalFilament = $lavoro->total_filament;
                $totalMins = $lavoro->total_minutes;
                $th = intdiv($totalMins, 60);
                $tm = $totalMins % 60;
            @endphp
            <div class="lavoro-summary" style="margin:1rem;margin-bottom:.5rem;">
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Filamento totale</div>
                    <div class="summary-value">{{ $totalFilament }} g</div>
                </div>
                <div>
                    <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Tempo totale</div>
                    <div class="summary-value">{{ $th }}h {{ $tm }}min</div>
                </div>
            </div>

            @if($lavoro->projects->isEmpty())
                <div style="padding:2rem;text-align:center;color:#9ca3af;">Nessun progetto associato.</div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Progetto</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Filam. unit.</th>
                            <th style="text-align:right;">Tempo unit.</th>
                            <th style="text-align:right;">Filam. tot.</th>
                            <th style="text-align:right;">Tempo tot.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lavoro->projects as $p)
                        @php
                            $qty = $p->pivot->quantita;
                            $filUnit = $p->filament_grams ?? 0;
                            $minsUnit = (($p->print_hours ?? 0) * 60 + ($p->print_minutes ?? 0));
                            $filTot = $filUnit * $qty;
                            $minsTot = $minsUnit * $qty;
                            $hu = intdiv($minsUnit, 60); $mu = $minsUnit % 60;
                            $ht = intdiv($minsTot, 60);  $mt = $minsTot % 60;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('backend.projects.show', $p) }}" class="table-name-link">
                                    {{ $p->name }}
                                </a>
                                @if($p->filament_type)
                                    <div style="font-size:.77rem;color:#9ca3af;">{{ $p->filament_type }}</div>
                                @endif
                            </td>
                            <td style="text-align:center;font-weight:700;">× {{ $qty }}</td>
                            <td style="text-align:right;font-size:.88rem;">{{ $filUnit }} g</td>
                            <td style="text-align:right;font-size:.88rem;">{{ $hu }}h {{ $mu }}min</td>
                            <td style="text-align:right;font-weight:600;color:#023059;">{{ $filTot }} g</td>
                            <td style="text-align:right;font-weight:600;color:#023059;">{{ $ht }}h {{ $mt }}min</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#eef1f8;">
                            <td colspan="4" style="font-weight:700;padding:.6rem 1rem;">Totali</td>
                            <td style="text-align:right;font-weight:700;color:#023059;padding:.6rem 1rem;">{{ $totalFilament }} g</td>
                            <td style="text-align:right;font-weight:700;color:#023059;padding:.6rem 1rem;">{{ $th }}h {{ $tm }}min</td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>

</div>

{{-- Delete --}}
<div style="margin-top:2rem;padding-top:1.5rem;border-top:2px solid #fee2e2;display:flex;justify-content:flex-end;">
    <form method="POST" action="{{ route('backend.lavori.destroy', $lavoro) }}"
          onsubmit="return confirm('Eliminare definitivamente il lavoro {{ $lavoro->numero }}?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
            Elimina Lavoro
        </button>
    </form>
</div>

@endsection
