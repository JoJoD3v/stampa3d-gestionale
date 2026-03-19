@extends('layouts.backend')

@section('title', 'Gestione Utenti')
@section('page-title', 'Gestione Utenti')

@section('content')

<div class="page-header">
    <h1>Utenti</h1>
    <a href="{{ route('backend.users.create') }}" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nuovo Utente
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Lista Utenti <span style="font-weight:400;color:#6b7280;font-size:0.85rem;">({{ $users->total() }} totali)</span></h2>
    </div>

    @if($users->isEmpty())
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            <p>Nessun utente trovato.<br>
                <a href="{{ route('backend.users.create') }}" style="color:#023059;font-weight:700;">Crea il primo utente</a>
            </p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Ruolo</th>
                        <th>Creato il</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr data-href="{{ route('backend.users.edit', $user) }}">
                        <td style="color:#9ca3af;font-size:0.8rem;">{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->surname }}</td>
                        <td style="color:#4b5563;">{{ $user->email }}</td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge badge-admin">Amministratore</span>
                            @else
                                <span class="badge badge-user">Utente</span>
                            @endif
                        </td>
                        <td style="color:#6b7280;font-size:0.82rem;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('backend.users.edit', $user) }}"
                                   class="btn btn-secondary btn-sm" title="Modifica">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Modifica
                                </a>

                                @if(auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('backend.users.destroy', $user) }}"
                                      onsubmit="return confirm('Eliminare l\'utente {{ addslashes($user->name . ' ' . $user->surname) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Elimina">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6"/><path d="M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                        </svg>
                                        Elimina
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div style="padding: 1rem 1.3rem; border-top: 1px solid #f0f3f8;">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
        @endif
    @endif
</div>

@endsection
