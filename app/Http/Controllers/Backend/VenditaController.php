<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Vendita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VenditaController extends Controller
{
    public function index(Request $request): View
    {
        $search  = $request->input('search', '');
        $vendite = Vendita::with('project')
            ->when($search, fn($q) => $q->whereHas(
                'project', fn($q2) => $q2->where('name', 'like', '%' . $search . '%')
            ))
            ->orderByDesc('data_vendita')
            ->paginate(20)
            ->withQueryString();

        $totale = Vendita::when($search, fn($q) => $q->whereHas(
            'project', fn($q2) => $q2->where('name', 'like', '%' . $search . '%')
        ))->sum('importo');

        return view('backend.vendite.index', compact('vendite', 'search', 'totale'));
    }

    public function create(): View
    {
        $projects = Project::orderBy('name')->get();
        return view('backend.vendite.create', compact('projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id'   => ['required', 'exists:projects,id'],
            'importo'      => ['required', 'numeric', 'min:0.01'],
            'data_vendita' => ['required', 'date'],
            'note'         => ['nullable', 'string', 'max:1000'],
        ]);

        Vendita::create($data);

        return redirect()->route('backend.vendite.index')
            ->with('success', 'Vendita registrata con successo.');
    }

    public function edit(Vendita $vendita): View
    {
        $projects = Project::orderBy('name')->get();
        return view('backend.vendite.edit', compact('vendita', 'projects'));
    }

    public function update(Request $request, Vendita $vendita): RedirectResponse
    {
        $data = $request->validate([
            'project_id'   => ['required', 'exists:projects,id'],
            'importo'      => ['required', 'numeric', 'min:0.01'],
            'data_vendita' => ['required', 'date'],
            'note'         => ['nullable', 'string', 'max:1000'],
        ]);

        $vendita->update($data);

        return redirect()->route('backend.vendite.index')
            ->with('success', 'Vendita aggiornata con successo.');
    }

    public function destroy(Vendita $vendita): RedirectResponse
    {
        $vendita->delete();

        return redirect()->route('backend.vendite.index')
            ->with('success', 'Vendita eliminata.');
    }
}
