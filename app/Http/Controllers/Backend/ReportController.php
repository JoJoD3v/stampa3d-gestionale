<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lavoro;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            : now()->endOfYear();

        // Ensure from <= to
        if ($dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo->startOfMonth()->copy(), $dateFrom->endOfMonth()->copy()];
        }

        $lavori = Lavoro::with('projects')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        // Build month-by-month stats
        $mesiItalia = [
            1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo', 4 => 'Aprile',
            5 => 'Maggio', 6 => 'Giugno', 7 => 'Luglio', 8 => 'Agosto',
            9 => 'Settembre', 10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre',
        ];

        // Enumerate all months in range so months with 0 data still appear
        $months = [];
        $cursor = $dateFrom->copy()->startOfMonth();
        while ($cursor->lte($dateTo)) {
            $key = $cursor->format('Y-m');
            $months[$key] = [
                'label'       => $mesiItalia[(int) $cursor->format('n')] . ' ' . $cursor->format('Y'),
                'lavori'      => 0,
                'entrate'     => 0,
                'filamento'   => 0,
                'progetti'    => 0,
            ];
            $cursor->addMonth();
        }

        foreach ($lavori as $lavoro) {
            $key = $lavoro->created_at->format('Y-m');
            if (!isset($months[$key])) continue;

            $months[$key]['lavori']++;
            $months[$key]['entrate']   += (float) ($lavoro->preventivo ?? 0);
            $months[$key]['filamento'] += $lavoro->total_filament;
            $months[$key]['progetti']  += $lavoro->projects->sum('pivot.quantita');
        }

        $totals = [
            'lavori'    => array_sum(array_column($months, 'lavori')),
            'entrate'   => array_sum(array_column($months, 'entrate')),
            'filamento' => array_sum(array_column($months, 'filamento')),
            'progetti'  => array_sum(array_column($months, 'progetti')),
        ];

        return view('backend.report.index', [
            'months'    => $months,
            'totals'    => $totals,
            'dateFrom'  => $dateFrom,
            'dateTo'    => $dateTo,
        ]);
    }
}
