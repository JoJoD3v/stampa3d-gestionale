<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lavoro;
use App\Models\Vendita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    // ── Entrate ──────────────────────────────────────────────────────────────
    public function entrate(Request $request): View
    {
        [$dateFrom, $dateTo] = $this->parseDates($request);

        $mesiBrevi = $this->mesiBrevi();

        $lavoriCompletati = Lavoro::with('customer')
            ->whereIn('status', ['completato', 'consegnato'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderByDesc('created_at')
            ->get();

        $vendite = Vendita::with('project')
            ->whereBetween('data_vendita', [$dateFrom, $dateTo])
            ->orderByDesc('data_vendita')
            ->get();

        $totaleLavori   = (float) $lavoriCompletati->sum('preventivo');
        $totaleVendite  = (float) $vendite->sum('importo');
        $totaleEntrate  = $totaleLavori + $totaleVendite;

        $monthlyMap = [];
        $cursor = $dateFrom->copy()->startOfMonth();
        while ($cursor->lte($dateTo)) {
            $monthlyMap[$cursor->format('Y-m')] = [
                'label'   => $mesiBrevi[(int) $cursor->format('n')] . ' ' . $cursor->format('Y'),
                'entrate' => 0.0,
            ];
            $cursor->addMonth();
        }
        foreach ($lavoriCompletati as $lav) {
            $key = $lav->created_at->format('Y-m');
            if (isset($monthlyMap[$key])) {
                $monthlyMap[$key]['entrate'] += (float) ($lav->preventivo ?? 0);
            }
        }
        foreach ($vendite as $v) {
            $key = $v->data_vendita->format('Y-m');
            if (isset($monthlyMap[$key])) {
                $monthlyMap[$key]['entrate'] += (float) $v->importo;
            }
        }

        $chartLabels = array_column($monthlyMap, 'label');
        $chartData   = array_values(array_map(fn($m) => round($m['entrate'], 2), $monthlyMap));

        return view('backend.report.entrate', compact(
            'lavoriCompletati', 'vendite',
            'totaleLavori', 'totaleVendite', 'totaleEntrate',
            'chartLabels', 'chartData', 'dateFrom', 'dateTo'
        ));
    }

    // ── Stampe Progetti ───────────────────────────────────────────────────────
    public function progetti(Request $request): View
    {
        [$dateFrom, $dateTo] = $this->parseDates($request);

        $progettiStampati = $this->queryProgetti($dateFrom, $dateTo)
            ->orderByDesc('total_qty')
            ->get();

        $chartLabels = $progettiStampati->take(10)->pluck('name')->toArray();
        $chartData   = $progettiStampati->take(10)->pluck('total_qty')->map(fn($v) => (int) $v)->toArray();

        return view('backend.report.progetti', compact(
            'progettiStampati', 'chartLabels', 'chartData', 'dateFrom', 'dateTo'
        ));
    }

    // ── Consumo Filo ──────────────────────────────────────────────────────────
    public function consumi(Request $request): View
    {
        [$dateFrom, $dateTo] = $this->parseDates($request);

        $progettiFilamento = $this->queryProgetti($dateFrom, $dateTo)
            ->orderByDesc('total_filamento')
            ->get();

        $chartLabels = $progettiFilamento->take(10)->pluck('name')->toArray();
        $chartData   = $progettiFilamento->take(10)->pluck('total_filamento')->map(fn($v) => (int) $v)->toArray();

        return view('backend.report.consumi', compact(
            'progettiFilamento', 'chartLabels', 'chartData', 'dateFrom', 'dateTo'
        ));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function parseDates(Request $request): array
    {
        $request->validate([
            'date_from' => ['nullable', 'date_format:Y-m'],
            'date_to'   => ['nullable', 'date_format:Y-m'],
        ]);

        $dateFrom = $request->filled('date_from')
            ? Carbon::createFromFormat('Y-m', $request->date_from)->startOfMonth()
            : now()->startOfYear();

        $dateTo = $request->filled('date_to')
            ? Carbon::createFromFormat('Y-m', $request->date_to)->endOfMonth()
            : now()->endOfMonth();

        if ($dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo->copy()->startOfMonth(), $dateFrom->copy()->endOfMonth()];
        }

        return [$dateFrom, $dateTo];
    }

    private function queryProgetti(Carbon $dateFrom, Carbon $dateTo)
    {
        return DB::table('lavoro_progetto')
            ->join('lavori', 'lavori.id', '=', 'lavoro_progetto.lavoro_id')
            ->join('projects', 'projects.id', '=', 'lavoro_progetto.project_id')
            ->whereBetween('lavori.created_at', [$dateFrom, $dateTo])
            ->groupBy('projects.id', 'projects.name', 'projects.filament_grams')
            ->select(
                'projects.id',
                'projects.name',
                DB::raw('COALESCE(projects.filament_grams, 0) as filament_grams'),
                DB::raw('SUM(lavoro_progetto.quantita) as total_qty'),
                DB::raw('SUM(lavoro_progetto.quantita * COALESCE(projects.filament_grams, 0)) as total_filamento')
            );
    }

    private function mesiBrevi(): array
    {
        return [
            1 => 'Gen', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mag', 6 => 'Giu', 7 => 'Lug', 8 => 'Ago',
            9 => 'Set', 10 => 'Ott', 11 => 'Nov', 12 => 'Dic',
        ];
    }
}

