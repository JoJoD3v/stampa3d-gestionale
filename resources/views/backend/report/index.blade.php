@extends('layouts.backend')

@section('title', 'Report')
@section('page-title', 'Report')

@section('content')

<div class="page-header">
    <h1>Report</h1>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
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

{{-- Totals --}}
<div class="stats-grid" style="margin-bottom:1.5rem;">

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="2" y="7" width="20" height="14" rx="2"/>
                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $totals['lavori'] }}</div>
            <div class="stat-label">Lavori nel periodo</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">€ {{ number_format($totals['entrate'], 2, ',', '.') }}</div>
            <div class="stat-label">Entrate totali</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $totals['progetti'] }}</div>
            <div class="stat-label">Pezzi prodotti</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            </svg>
        </div>
        <div>
            <div class="stat-number">
                @if($totals['filamento'] >= 1000)
                    {{ number_format($totals['filamento'] / 1000, 2, ',', '.') }} kg
                @else
                    {{ $totals['filamento'] }} g
                @endif
            </div>
            <div class="stat-label">Filamento utilizzato</div>
        </div>
    </div>

</div>

{{-- Month table --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div class="card-header">
        <h2>Dettaglio mensile</h2>
    </div>
    @if(empty(array_filter($months, fn($m) => $m['lavori'] > 0)))
        <div style="padding:2rem;text-align:center;color:#9ca3af;">Nessun lavoro trovato nel periodo selezionato.</div>
    @else
    <table class="data-table">
        <thead>
            <tr>
                <th>Mese</th>
                <th style="text-align:right;">Lavori</th>
                <th style="text-align:right;">Entrate</th>
                <th style="text-align:right;">Pezzi prodotti</th>
                <th style="text-align:right;">Filamento</th>
                <th>Barra entrate</th>
            </tr>
        </thead>
        <tbody>
            @php $maxEntrate = max(array_column($months, 'entrate') ?: [1]); @endphp
            @foreach($months as $key => $m)
            <tr style="{{ $m['lavori'] === 0 ? 'opacity:.45;' : '' }}">
                <td style="font-weight:{{ $m['lavori'] > 0 ? '600' : '400' }};color:#{{ $m['lavori'] > 0 ? '023059' : '9ca3af' }};">
                    {{ $m['label'] }}
                </td>
                <td style="text-align:right;">
                    @if($m['lavori'] > 0)
                        <span style="font-weight:700;">{{ $m['lavori'] }}</span>
                    @else
                        <span style="color:#9ca3af;">—</span>
                    @endif
                </td>
                <td style="text-align:right;font-weight:700;color:#023059;">
                    @if($m['entrate'] > 0)
                        € {{ number_format($m['entrate'], 2, ',', '.') }}
                    @else
                        <span style="color:#9ca3af;font-weight:400;">—</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    @if($m['progetti'] > 0)
                        {{ $m['progetti'] }}
                    @else
                        <span style="color:#9ca3af;">—</span>
                    @endif
                </td>
                <td style="text-align:right;font-size:.88rem;color:#374151;">
                    @if($m['filamento'] > 0)
                        @if($m['filamento'] >= 1000)
                            {{ number_format($m['filamento'] / 1000, 2, ',', '.') }} kg
                        @else
                            {{ $m['filamento'] }} g
                        @endif
                    @else
                        <span style="color:#9ca3af;">—</span>
                    @endif
                </td>
                <td style="padding:.6rem 1rem;min-width:130px;">
                    @if($m['entrate'] > 0 && $maxEntrate > 0)
                        @php $pct = round($m['entrate'] / $maxEntrate * 100); @endphp
                        <div style="background:#e8eef8;border-radius:4px;height:10px;overflow:hidden;">
                            <div style="background:#023059;width:{{ $pct }}%;height:100%;border-radius:4px;transition:width .3s;"></div>
                        </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#eef1f8;font-weight:700;">
                <td style="padding:.65rem 1rem;color:#023059;">Totali</td>
                <td style="text-align:right;padding:.65rem 1rem;">{{ $totals['lavori'] }}</td>
                <td style="text-align:right;padding:.65rem 1rem;color:#023059;">€ {{ number_format($totals['entrate'], 2, ',', '.') }}</td>
                <td style="text-align:right;padding:.65rem 1rem;">{{ $totals['progetti'] }}</td>
                <td style="text-align:right;padding:.65rem 1rem;color:#374151;">
                    @if($totals['filamento'] >= 1000)
                        {{ number_format($totals['filamento'] / 1000, 2, ',', '.') }} kg
                    @else
                        {{ $totals['filamento'] }} g
                    @endif
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @endif
</div>

@endsection
