{{--
    Partial: report/_subnav.blade.php
    Vars:
      $active  — 'entrate' | 'progetti' | 'consumi'
--}}
<style>
.report-tabs { display: flex; gap: 0; margin-bottom: 1.5rem; border-bottom: 2px solid #e4e8f0; }
.report-tab {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .6rem 1.25rem;
    font-size: .88rem;
    font-weight: 500;
    color: #6b7280;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: color .15s, border-color .15s;
    white-space: nowrap;
}
.report-tab svg { width: 15px; height: 15px; flex-shrink: 0; }
.report-tab:hover { color: #023059; border-bottom-color: #b4c0d9; }
.report-tab.active { color: #023059; font-weight: 700; border-bottom-color: #023059; }
</style>

<div class="report-tabs">

    <a href="{{ route('backend.report.entrate') }}"
       class="report-tab {{ ($active ?? '') === 'entrate' ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
        Entrate
    </a>

    <a href="{{ route('backend.report.progetti') }}"
       class="report-tab {{ ($active ?? '') === 'progetti' ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
        Stampe Progetti
    </a>

    <a href="{{ route('backend.report.consumi') }}"
       class="report-tab {{ ($active ?? '') === 'consumi' ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
        Consumo Filo
    </a>

</div>
