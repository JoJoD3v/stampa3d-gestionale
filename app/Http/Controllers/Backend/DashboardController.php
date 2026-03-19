<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lavoro;
use App\Models\Printer;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $printersInUso = Printer::where('status', 'in_uso')
            ->with(['lavoroAttivo.customer', 'lavoroAttivo.projects'])
            ->orderBy('name')
            ->get();

        $printersSpente = Printer::where('status', 'spenta')
            ->orderBy('name')
            ->get();

        $clientiCount = Customer::count();
        $lavoriAttivi = Lavoro::whereIn('status', ['confermato', 'in_lavorazione'])->count();

        return view('backend.dashboard', compact(
            'printersInUso',
            'printersSpente',
            'clientiCount',
            'lavoriAttivi'
        ));
    }
}
