@extends('layouts.backend')

@section('title', 'Report')
@section('page-title', 'Report')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#6b7280';

    // 1. Entrate mensili — bar verticale
    var entrateEl = document.getElementById('chartEntrate');
    if (entrateEl) {
        new Chart(entrateEl, {
            type: 'bar',
            data: {
                labels: @json($chartEntrateLabels),
                datasets: [{
                    label: 'Entrate (€)',
                    data: @json($chartEntrateData),
                    backgroundColor: 'rgba(2, 48, 89, 0.85)',
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return '€ ' + ctx.parsed.y.toLocaleString('it-IT', {minimumFractionDigits:2, maximumFractionDigits:2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(v) { return '€ ' + v.toLocaleString('it-IT'); } },
                        grid: { color: '#f0f4fa' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Progetti stampati — bar orizzontale
    var progettiEl = document.getElementById('chartProgetti');
    if (progettiEl) {
        new Chart(progettiEl, {
            type: 'bar',
            data: {
                labels: @json($chartProgettiLabels),
                datasets: [{
                    label: 'Pezzi stampati',
                    data: @json($chartProgettiData),
                    backgroundColor: 'rgba(5, 150, 105, 0.85)',
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#f0f4fa' }
                    },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // 3. Consumo filo — bar orizzontale
    var filoEl = document.getElementById('chartFilo');
    if (filoEl) {
        new Chart(filoEl, {
            type: 'bar',
            data: {
                labels: @json($chartFiloLabels),
                datasets: [{
                    label: 'Filamento (g)',
                    data: @json($chartFiloData),
                    backgroundColor: 'rgba(217, 119, 6, 0.85)',
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                var v = ctx.parsed.x;
                                return v >= 1000 ? (v / 1000).toFixed(2) + ' kg' : v + ' g';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(v) {
                                return v >= 1000 ? (v / 1000).toFixed(1) + ' kg' : v + ' g';
                            }
                        },
                        grid: { color: '#f0f4fa' }
                    },
                    y: { grid: { display: false } }
                }
            }
        });
    }
}());
</script>
@endpush

@section('content')

<div class="page-header">
    <h1>Report</h1>
</div>

{{-- ── Filtro date ─────────────────────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:1.75rem;">
    <div class="card-body" style="padding:.9rem 1.25rem;">
        <form method="GET" action="{{ route('backend.report') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label style="font-size:.8rem;color:#6b7280;font-weight:600;display:block;margin-bottom:.3rem;text-transform:uppercase;letter-spacing:.05em;">Da</label>
                <input type="month" name="date_from"
                       value="{{ $dateFrom->format('Y-m') }}"
                       class="form-control"
                       style="padding:.4rem .65rem;border:1px solid #d1d5db;border-radius:6px;font-size:.9rem;">
            </div>
            <div>
                <label style="font-size:.8rem;color:#6b7280;font-weight:600;display:block;margin-bottom:.3rem;text-transform:uppercase;letter-spacing:.05em;">A</label>
                <input type="month" name="date_to"
                       value="{{ $dateTo->format('Y-m') }}"
                       class="form-control"
                       style="padding:.4rem .65rem;border:1px solid #d1d5db;border-radius:6px;font-size:.9rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="align-self:flex-end;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Filtra
            </button>
            @if(request()->hasAny(['date_from','date_to']))
            <a href="{{ route('backend.report') }}" class="btn btn-secondary" style="align-self:flex-end;">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- ══════════════════════════════ SEZIONE ENTRATE ══════════════════════════════ --}}
<div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#023059" stroke-width="2">
        <line x1="12" y1="1" x2="12" y2="23"/>
        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
    </svg>
    <h2 style="margin:0;font-size:1.1rem;font-weight:700;color:#023059;text-transform:uppercase;letter-spacing:.04em;">Entrate</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.25rem;margin-bottom:1.25rem;">
    {{-- Stat cards --}}
    <div style="display:flex;flex-direction:column;gap:.75rem;">
        <div class="stat-card" style="flex:1;">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div>
                <div class="stat-number">€ {{ number_format($totaleEntrate, 2, ',', '.') }}</div>
                <div class="stat-label">Totale entrate</div>
            </div>
        </div>
        <div class="stat-card" style="flex:1;">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                </svg>
            </div>
            <div>
                <div class="stat-number">{{ $lavoriCompletati->count() }}</div>
                <div class="stat-label">Lavori completati</div>
            </div>
        </div>
    </div>
    {{-- Entrate bar chart --}}
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="padding:.65rem 1.1rem .35rem;font-size:.8rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f0f0f0;">
            Entrate mensili
        </div>
        <div style="padding:1rem;height:220px;position:relative;">
            <canvas id="chartEntrate"></canvas>
        </div>
    </div>
</div>

{{-- Tabella lavori completati --}}
<div class="card" style="padding:0;overflow:hidden;margin-bottom:2.5rem;">
    <div class="card-header">
        <h2>Elenco lavori completati / consegnati</h2>
        <span style="font-size:.82rem;color:#6b7280;">{{ $lavoriCompletati->count() }} lavori &middot; € {{ number_format($totaleEntrate, 2, ',', '.') }}</span>
    </div>
    @if($lavoriCompletati->isEmpty())
        <div style="padding:2.5rem;text-align:center;color:#9ca3af;font-size:.95rem;">
            Nessun lavoro completato nel periodo selezionato.
        </div>
    @else
    <table class="data-table">
        <thead>
            <tr>
                <th>N° Lavoro</th>
                <th>Cliente</th>
                <th>Data</th>
                <th style="text-align:right;">Preventivo</th>
                <th>Stato</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lavoriCompletati as $lav)
            <tr data-href="{{ route('backend.lavori.edit', $lav) }}">
                <td style="font-weight:600;color:#023059;">#{{ $lav->id }}</td>
                <td>{{ $lav->customer?->name ?? '—' }}</td>
                <td style="color:#6b7280;font-size:.88rem;">{{ $lav->created_at->format('d/m/Y') }}</td>
                <td style="text-align:right;font-weight:700;color:#023059;">
                    @if($lav->preventivo)
                        € {{ number_format($lav->preventivo, 2, ',', '.') }}
                    @else
                        <span style="color:#9ca3af;font-weight:400;">—</span>
                    @endif
                </td>
                <td>
                    <span class="status-lavoro status-{{ $lav->status }}">
                        {{ \App\Models\Lavoro::STATUS_LABELS[$lav->status] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- ══════════════════════════════ SEZIONE PROGETTI ══════════════════════════════ --}}
<div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
    </svg>
    <h2 style="margin:0;font-size:1.1rem;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:.04em;">Stampe Progetti</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:2.5rem;">
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="padding:.65rem 1.1rem .35rem;font-size:.8rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f0f0f0;">
            Top 10 progetti per quantità
        </div>
        <div style="padding:1rem;height:300px;position:relative;">
            @if($progettiStampati->isEmpty())
                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9ca3af;font-size:.9rem;">Nessun dato</div>
            @else
                <canvas id="chartProgetti"></canvas>
            @endif
        </div>
    </div>
    <div class="card" style="padding:0;overflow:hidden;overflow-y:auto;max-height:360px;">
        @if($progettiStampati->isEmpty())
            <div style="padding:2.5rem;text-align:center;color:#9ca3af;font-size:.95rem;">Nessun progetto stampato nel periodo selezionato.</div>
        @else
        <table class="data-table">
            <thead style="position:sticky;top:0;background:#fff;z-index:1;">
                <tr>
                    <th>Progetto</th>
                    <th style="text-align:right;">Pezzi</th>
                    <th style="text-align:right;">Filo/un.</th>
                    <th style="text-align:right;">Filo tot.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($progettiStampati as $p)
                <tr>
                    <td style="font-weight:500;">{{ $p->name }}</td>
                    <td style="text-align:right;font-weight:700;color:#059669;">{{ $p->total_qty }}</td>
                    <td style="text-align:right;font-size:.85rem;color:#6b7280;">
                        @if($p->filament_grams > 0) {{ $p->filament_grams }} g @else — @endif
                    </td>
                    <td style="text-align:right;font-size:.88rem;font-weight:600;color:#374151;">
                        @if($p->total_filamento > 0)
                            @if($p->total_filamento >= 1000)
                                {{ number_format($p->total_filamento / 1000, 2, ',', '.') }} kg
                            @else
                                {{ (int)$p->total_filamento }} g
                            @endif
                        @else — @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ══════════════════════════════ SEZIONE CONSUMO FILO ══════════════════════════ --}}
<div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1rem;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
    </svg>
    <h2 style="margin:0;font-size:1.1rem;font-weight:700;color:#d97706;text-transform:uppercase;letter-spacing:.04em;">Consumo Filo</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:2rem;">
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="padding:.65rem 1.1rem .35rem;font-size:.8rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f0f0f0;">
            Top 10 progetti per filamento
        </div>
        <div style="padding:1rem;height:300px;position:relative;">
            @if($progettiFilamento->isEmpty())
                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9ca3af;font-size:.9rem;">Nessun dato</div>
            @else
                <canvas id="chartFilo"></canvas>
            @endif
        </div>
    </div>
    <div class="card" style="padding:0;overflow:hidden;overflow-y:auto;max-height:360px;">
        @if($progettiFilamento->isEmpty())
            <div style="padding:2.5rem;text-align:center;color:#9ca3af;font-size:.95rem;">Nessun dato sul consumo filo nel periodo selezionato.</div>
        @else
        <table class="data-table">
            <thead style="position:sticky;top:0;background:#fff;z-index:1;">
                <tr>
                    <th>Progetto</th>
                    <th style="text-align:right;">Filo tot.</th>
                    <th style="text-align:right;">Pezzi</th>
                    <th style="text-align:right;">Filo/un.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($progettiFilamento as $p)
                <tr>
                    <td style="font-weight:500;">{{ $p->name }}</td>
                    <td style="text-align:right;font-weight:700;color:#d97706;">
                        @if($p->total_filamento >= 1000)
                            {{ number_format($p->total_filamento / 1000, 2, ',', '.') }} kg
                        @else
                            {{ (int)$p->total_filamento }} g
                        @endif
                    </td>
                    <td style="text-align:right;color:#6b7280;">{{ $p->total_qty }}</td>
                    <td style="text-align:right;font-size:.85rem;color:#6b7280;">
                        @if($p->filament_grams > 0) {{ $p->filament_grams }} g @else — @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

@endsection
