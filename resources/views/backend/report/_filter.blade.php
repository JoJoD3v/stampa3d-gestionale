{{--
    Partial: report/_filter.blade.php
    Vars:
      $route    — named route string, e.g. 'backend.report.entrate'
      $dateFrom — Carbon passed from controller
      $dateTo   — Carbon passed from controller
--}}
<form method="GET" action="{{ route($route) }}"
      style="display:flex;align-items:flex-end;gap:.75rem;flex-wrap:wrap;margin-bottom:1.5rem;">

    <div style="display:flex;flex-direction:column;gap:.3rem;">
        <label style="font-size:.78rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;">Da</label>
        <input type="month"
               name="date_from"
               value="{{ $dateFrom->format('Y-m') }}"
               style="border:1px solid #d1d5db;border-radius:6px;padding:.42rem .7rem;font-size:.88rem;color:#374151;background:#fff;cursor:pointer;">
    </div>

    <div style="display:flex;flex-direction:column;gap:.3rem;">
        <label style="font-size:.78rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;">A</label>
        <input type="month"
               name="date_to"
               value="{{ $dateTo->format('Y-m') }}"
               style="border:1px solid #d1d5db;border-radius:6px;padding:.42rem .7rem;font-size:.88rem;color:#374151;background:#fff;cursor:pointer;">
    </div>

    <button type="submit"
            style="padding:.46rem 1.1rem;background:#023059;color:#fff;border:none;border-radius:6px;font-size:.88rem;font-weight:500;cursor:pointer;white-space:nowrap;">
        Filtra
    </button>

    <a href="{{ route($route) }}"
       style="padding:.46rem .9rem;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.85rem;border:1px solid #e5e7eb;white-space:nowrap;">
        Reset
    </a>

    <span style="font-size:.82rem;color:#9ca3af;align-self:center;white-space:nowrap;">
        {{ $dateFrom->locale('it')->isoFormat('MMMM YYYY') }}
        &mdash;
        {{ $dateTo->locale('it')->isoFormat('MMMM YYYY') }}
    </span>

</form>
