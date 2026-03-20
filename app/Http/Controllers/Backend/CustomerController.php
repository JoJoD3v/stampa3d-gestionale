<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::orderBy('cognome')->orderBy('nome')->paginate(20);

        return view('backend.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('backend.customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome'      => ['required', 'string', 'max:100'],
            'cognome'   => ['nullable', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:200'],
            'telefono'  => ['nullable', 'string', 'max:30'],
            'indirizzo' => ['nullable', 'string', 'max:255'],
            'citta'     => ['nullable', 'string', 'max:100'],
            'cap'       => ['nullable', 'string', 'max:10'],
            'provincia' => ['nullable', 'string', 'max:5'],
            'origine'   => ['nullable', 'string', 'max:100'],
            'note'      => ['nullable', 'string'],
        ]);

        $customer = Customer::create($request->only([
            'nome', 'cognome', 'email', 'telefono',
            'indirizzo', 'citta', 'cap', 'provincia',
            'origine', 'note',
        ]));

        return redirect()
            ->route('backend.customers.show', $customer)
            ->with('success', 'Cliente creato con successo.');
    }

    public function show(Customer $customer): View
    {
        return view('backend.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('backend.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'nome'      => ['required', 'string', 'max:100'],
            'cognome'   => ['nullable', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:200'],
            'telefono'  => ['nullable', 'string', 'max:30'],
            'indirizzo' => ['nullable', 'string', 'max:255'],
            'citta'     => ['nullable', 'string', 'max:100'],
            'cap'       => ['nullable', 'string', 'max:10'],
            'provincia' => ['nullable', 'string', 'max:5'],
            'origine'   => ['nullable', 'string', 'max:100'],
            'note'      => ['nullable', 'string'],
        ]);

        $customer->update($request->only([
            'nome', 'cognome', 'email', 'telefono',
            'indirizzo', 'citta', 'cap', 'provincia',
            'origine', 'note',
        ]));

        return redirect()
            ->route('backend.customers.show', $customer)
            ->with('success', 'Cliente aggiornato con successo.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('backend.customers.index')
            ->with('success', 'Cliente eliminato con successo.');
    }
}
