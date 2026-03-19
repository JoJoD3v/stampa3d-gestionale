<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lavoro;
use App\Models\Project;
use Illuminate\Http\Request;

class LavoroController extends Controller
{
    public function index()
    {
        $lavori = Lavoro::with(['customer', 'projects'])
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
        $lavoro->load(['customer', 'projects']);

        return view('backend.lavori.show', compact('lavoro'));
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
}
