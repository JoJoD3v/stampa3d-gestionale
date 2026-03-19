<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('surname')->orderBy('name')->paginate(15);

        return view('backend.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('backend.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'surname'  => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'surname'  => $validated['surname'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()
            ->route('backend.users.index')
            ->with('success', 'Utente creato con successo.');
    }

    public function edit(User $user): View
    {
        return view('backend.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'surname' => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name'     => $validated['name'],
            'surname'  => $validated['surname'],
            'email'    => $validated['email'],
            'is_admin' => $request->boolean('is_admin'),
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Password::min(8)],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()
            ->route('backend.users.index')
            ->with('success', 'Utente aggiornato con successo.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Non puoi eliminare il tuo stesso account.');
        }

        $user->delete();

        return redirect()
            ->route('backend.users.index')
            ->with('success', 'Utente eliminato con successo.');
    }
}
