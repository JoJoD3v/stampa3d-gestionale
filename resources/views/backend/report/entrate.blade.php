@extends('layouts.backend')

@section('title', 'Report — Entrate')
@section('page-title', 'Report')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#6b7280';

    var el = document.getElementById('chartEntrate');
    if (!el) return;

    new Chart(el, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Entrate (€)',
                data: @json($chartData),
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
}());
</script>
@endpush

@section('content')

{{-- ── Tab nav ─────────────────────────────────────────────────────────────── --}}
@include('backend.report._subnav', ['active' => 'entrate'])

{{-- ── Filtro date ─────────────────────────────────────────────────────────── --}}
@include('backend.report._filter', ['route' => 'backend.report.entrate'])

{{-- ── Stat cards ──────────────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="stat-card">
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
    <div class="stat-card">
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
    @if($lavoriCompletati->count() > 0)
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">€ {{ number_format($totaleEntrate / $lavoriCompletati->count(), 2, ',', '.') }}</div>
            <div class="stat-label">Media per lavoro</div>
        </div>
    </div>
    @endif
</div>

{{-- ── Chart entrate mensili ───────────────────────────────────────────────── --}}
<div class="card" style="padding:0;overflow:hidden;margin-bottom:1.5rem;">
    <div style="padding:.65rem 1.1rem .35rem;font-size:.8rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f0f0f0;">
        Entrate mensili
    </div>
    <div style="padding:1.25rem;height:260px;position:relative;">
        <canvas id="chartEntrate"></canvas>
    </div>
</div>

{{-- ── Tabella lavori ──────────────────────────────────────────────────────── --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div class="card-header">
        <h2>Elenco lavori completati / consegnati</h2>
        <span style="font-size:.82rem;color:#6b7280;">{{ $lavoriCompletati->count() }} lavori &middot; {{ $dateFrom->format('M Y') }} &ndash; {{ $dateTo->format('M Y') }}</span>
    </div>
    @if($lavoriCompletati->isEmpty())
        <div style="padding:3rem;text-align:center;color:#9ca3af;font-size:.95rem;">
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
        <tfoot>
            <tr style="background:#eef1f8;font-weight:700;">
                <td colspan="3" style="padding:.65rem 1rem;color:#023059;">Totale</td>
                <td style="text-align:right;padding:.65rem 1rem;color:#023059;">€ {{ number_format($totaleEntrate, 2, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @endif
</div>

@endsection
