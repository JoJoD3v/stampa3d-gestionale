<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrinterController extends Controller
{
    public function index(): View
    {
        $printers = Printer::orderBy('name')->paginate(15);

        return view('backend.printers.index', compact('printers'));
    }

    public function create(): View
    {
        return view('backend.printers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:150'],
            'model' => ['required', 'string', 'max:150'],
        ]);

        Printer::create([
            'name'   => $request->name,
            'model'  => $request->model,
            'status' => 'spenta',
        ]);

        return redirect()
            ->route('backend.printers.index')
            ->with('success', 'Stampante aggiunta con successo.');
    }

    public function edit(Printer $printer): View
    {
        return view('backend.printers.edit', compact('printer'));
    }

    public function update(Request $request, Printer $printer): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:150'],
            'model' => ['required', 'string', 'max:150'],
        ]);

        $printer->update([
            'name'  => $request->name,
            'model' => $request->model,
        ]);

        return redirect()
            ->route('backend.printers.index')
            ->with('success', 'Stampante aggiornata con successo.');
    }

    public function destroy(Printer $printer): RedirectResponse
    {
        $printer->delete();

        return redirect()
            ->route('backend.printers.index')
            ->with('success', 'Stampante eliminata con successo.');
    }
}
