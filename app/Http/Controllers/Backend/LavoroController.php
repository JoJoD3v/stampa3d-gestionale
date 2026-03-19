<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lavoro;
use App\Models\Printer;
use App\Models\Project;
use Illuminate\Http\Request;

class LavoroController extends Controller
{
    public function index()
    {
        $lavori = Lavoro::with(['customer', 'projects', 'printer'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('backend.lavori.index', compact('lavori'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $projects  = Project::orderBy('name')->get();

        return view('backend.lavori.create', compact('customers', 'projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'preventivo'  => ['nullable', 'numeric', 'min:0'],
            'scadenza'    => ['nullable', 'date'],
            'status'      => ['required', 'in:bozza,confermato,in_lavorazione,completato,consegnato'],
            'note'        => ['nullable', 'string', 'max:2000'],
            'righe'       => ['required', 'array', 'min:1'],
            'righe.*.project_id' => ['required', 'exists:projects,id'],
            'righe.*.quantita'   => ['required', 'integer', 'min:1'],
        ]);

        $lavoro = Lavoro::create([
            'customer_id' => $data['customer_id'],
            'preventivo'  => $data['preventivo'] ?? null,
            'scadenza'    => $data['scadenza'] ?? null,
            'status'      => $data['status'],
            'note'        => $data['note'] ?? null,
        ]);

        $syncData = [];
        foreach ($data['righe'] as $riga) {
            $syncData[$riga['project_id']] = ['quantita' => (int) $riga['quantita']];
        }
        $lavoro->projects()->sync($syncData);

        return redirect()->route('backend.lavori.index')
            ->with('success', "Lavoro {$lavoro->numero} creato con successo.");
    }

    public function show(Lavoro $lavoro)
    {
        $lavoro->load(['customer', 'projects', 'printer']);
        $printersDisponibili = Printer::where('status', 'spenta')->orderBy('name')->get();

        return view('backend.lavori.show', compact('lavoro', 'printersDisponibili'));
    }

    public function edit(Lavoro $lavoro)
    {
        $lavoro->load('projects');
        $customers = Customer::orderBy('name')->get();
        $projects  = Project::orderBy('name')->get();

        return view('backend.lavori.edit', compact('lavoro', 'customers', 'projects'));
    }

    public function update(Request $request, Lavoro $lavoro)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'preventivo'  => ['nullable', 'numeric', 'min:0'],
            'scadenza'    => ['nullable', 'date'],
            'status'      => ['required', 'in:bozza,confermato,in_lavorazione,completato,consegnato'],
            'note'        => ['nullable', 'string', 'max:2000'],
            'righe'       => ['required', 'array', 'min:1'],
            'righe.*.project_id' => ['required', 'exists:projects,id'],
            'righe.*.quantita'   => ['required', 'integer', 'min:1'],
        ]);

        $lavoro->update([
            'customer_id' => $data['customer_id'],
            'preventivo'  => $data['preventivo'] ?? null,
            'scadenza'    => $data['scadenza'] ?? null,
            'status'      => $data['status'],
            'note'        => $data['note'] ?? null,
        ]);

        $syncData = [];
        foreach ($data['righe'] as $riga) {
            $syncData[$riga['project_id']] = ['quantita' => (int) $riga['quantita']];
        }
        $lavoro->projects()->sync($syncData);

        return redirect()->route('backend.lavori.show', $lavoro)
            ->with('success', "Lavoro {$lavoro->numero} aggiornato con successo.");
    }

    public function destroy(Lavoro $lavoro)
    {
        $lavoro->projects()->detach();
        $lavoro->delete();

        return redirect()->route('backend.lavori.index')
            ->with('success', 'Lavoro eliminato con successo.');
    }

    public function assignPrinter(Request $request, Lavoro $lavoro)
    {
        $data = $request->validate([
            'printer_id' => ['required', 'exists:printers,id'],
        ]);

        $printer = Printer::findOrFail($data['printer_id']);

        if ($printer->status !== 'spenta') {
            return back()->with('error', 'La stampante selezionata non è disponibile (non è spenta).');
        }

        // Release previous printer if one was already assigned
        if ($lavoro->printer_id && $lavoro->printer_id !== $printer->id) {
            Printer::find($lavoro->printer_id)?->update(['status' => 'spenta']);
        }

        $lavoro->update([
            'printer_id'      => $printer->id,
            'avvio_stampa_at' => now(),
        ]);

        $printer->update(['status' => 'in_uso']);

        return back()->with('success', "Stampante {$printer->name} assegnata al lavoro {$lavoro->numero}.");
    }

    public function releasePrinter(Lavoro $lavoro)
    {
        if (!$lavoro->printer_id) {
            return back()->with('error', 'Nessuna stampante assegnata a questo lavoro.');
        }

        $printer = Printer::find($lavoro->printer_id);

        $lavoro->update([
            'printer_id'      => null,
            'avvio_stampa_at' => null,
        ]);

        if ($printer) {
            $printer->update(['status' => 'spenta']);
        }

        return back()->with('success', 'Stampante liberata.');
    }
}
