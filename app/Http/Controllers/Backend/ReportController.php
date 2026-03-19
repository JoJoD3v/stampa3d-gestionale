<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lavoro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
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

        $mesiBrevi = [
            1 => 'Gen', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mag', 6 => 'Giu', 7 => 'Lug', 8 => 'Ago',
            9 => 'Set', 10 => 'Ott', 11 => 'Nov', 12 => 'Dic',
        ];

        // ── Entrate: lavori completati/consegnati nel range ──────────────────
        $lavoriCompletati = Lavoro::with('customer')
            ->whereIn('status', ['completato', 'consegnato'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderByDesc('created_at')
            ->get();

        $totaleEntrate = (float) $lavoriCompletati->sum('preventivo');

        // Monthly entrate for chart (all months in range, including empty)
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

        $chartEntrateLabels = array_column($monthlyMap, 'label');
        $chartEntrateData   = array_values(array_map(fn($m) => round($m['entrate'], 2), $monthlyMap));

        // ── Progetti stampati nel range (tutti i lavori, non solo completati) ─
        $progettiStampati = DB::table('lavoro_progetto')
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
            )
            ->orderByDesc('total_qty')
            ->get();

        $progettiFilamento = $progettiStampati->sortByDesc('total_filamento')->values();

        $chartProgettiLabels = $progettiStampati->take(10)->pluck('name')->toArray();
        $chartProgettiData   = $progettiStampati->take(10)->pluck('total_qty')->map(fn($v) => (int) $v)->toArray();

        $chartFiloLabels = $progettiFilamento->take(10)->pluck('name')->toArray();
        $chartFiloData   = $progettiFilamento->take(10)->pluck('total_filamento')->map(fn($v) => (int) $v)->toArray();

        return view('backend.report.index', [
            'lavoriCompletati'  => $lavoriCompletati,
            'totaleEntrate'     => $totaleEntrate,
            'progettiStampati'  => $progettiStampati,
            'progettiFilamento' => $progettiFilamento,
            'chartEntrateLabels'=> $chartEntrateLabels,
            'chartEntrateData'  => $chartEntrateData,
            'chartProgettiLabels'=> $chartProgettiLabels,
            'chartProgettiData' => $chartProgettiData,
            'chartFiloLabels'   => $chartFiloLabels,
            'chartFiloData'     => $chartFiloData,
            'dateFrom'          => $dateFrom,
            'dateTo'            => $dateTo,
        ]);
    }
}

