@extends('layouts.backend')

@section('title', 'Report — Stampe Progetti')
@section('page-title', 'Report')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#6b7280';

    var el = document.getElementById('chartProgetti');
    if (!el) return;

    new Chart(el, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pezzi stampati',
                data: @json($chartData),
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
}());
</script>
@endpush

@section('content')

{{-- ── Tab nav ─────────────────────────────────────────────────────────────── --}}
@include('backend.report._subnav', ['active' => 'progetti'])

{{-- ── Filtro date ─────────────────────────────────────────────────────────── --}}
@include('backend.report._filter', ['route' => 'backend.report.progetti'])

{{-- ── Stat cards ──────────────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $progettiStampati->count() }}</div>
            <div class="stat-label">Progetti distinti</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $progettiStampati->sum('total_qty') }}</div>
            <div class="stat-label">Pezzi totali stampati</div>
        </div>
    </div>
</div>

{{-- ── Layout: chart + tabella ────────────────────────────────────────────── --}}
@if($progettiStampati->isEmpty())
    <div class="card" style="padding:3rem;text-align:center;color:#9ca3af;font-size:.95rem;">
        Nessun progetto stampato nel periodo selezionato.
    </div>
@else
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;align-items:start;">

    {{-- Chart --}}
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="padding:.65rem 1.1rem .35rem;font-size:.8rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f0f0f0;">
            Top {{ min(10, $progettiStampati->count()) }} per quantità
        </div>
        <div style="padding:1.25rem;height:{{ max(220, min(10, $progettiStampati->count()) * 38 + 60) }}px;position:relative;">
            <canvas id="chartProgetti"></canvas>
        </div>
    </div>

    {{-- Tabella --}}
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="padding:.65rem 1.1rem .35rem;font-size:.8rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #f0f0f0;">
            Tutti i progetti
        </div>
        <div style="overflow-y:auto;max-height:500px;">
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
                            @if($p->filament_grams > 0) {{ $p->filament_grams }} g @else &mdash; @endif
                        </td>
                        <td style="text-align:right;font-weight:600;font-size:.88rem;color:#374151;">
                            @if($p->total_filamento > 0)
                                @if($p->total_filamento >= 1000)
                                    {{ number_format($p->total_filamento / 1000, 2, ',', '.') }} kg
                                @else
                                    {{ (int)$p->total_filamento }} g
                                @endif
                            @else &mdash; @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#eef7f3;font-weight:700;">
                        <td style="padding:.65rem 1rem;color:#059669;">Totale</td>
                        <td style="text-align:right;padding:.65rem 1rem;color:#059669;">{{ $progettiStampati->sum('total_qty') }}</td>
                        <td></td>
                        <td style="text-align:right;padding:.65rem 1rem;font-size:.88rem;color:#374151;">
                            @php $totFilo = $progettiStampati->sum('total_filamento'); @endphp
                            @if($totFilo >= 1000)
                                {{ number_format($totFilo / 1000, 2, ',', '.') }} kg
                            @else
                                {{ (int)$totFilo }} g
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endif

@endsection
