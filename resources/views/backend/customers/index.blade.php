@extends('layouts.backend')

@section('title', 'Gestione Clienti')
@section('page-title', 'Gestione Clienti')

@section('content')

<div class="page-header">
    <h1>Clienti</h1>
    <a href="{{ route('backend.customers.create') }}" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nuovo Cliente
    </a>
</div>

@if($customers->isEmpty())
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        <p>Nessun cliente trovato.<br>
            <a href="{{ route('backend.customers.create') }}" style="color:#023059;font-weight:700;">Aggiungi il primo cliente</a>
        </p>
    </div>
@else
    <div class="card" style="padding:0;overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Contatti</th>
                    <th>Indirizzo</th>
                    <th>Origine</th>
                    <th style="width:140px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr data-href="{{ route('backend.customers.show', $customer) }}">
                    <td>
                        <a href="{{ route('backend.customers.show', $customer) }}" class="table-name-link">
                            {{ $customer->full_name }}
                        </a>
                    </td>
                    <td>
                        @if($customer->email)
                            <div class="table-sub-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                {{ $customer->email }}
                            </div>
                        @endif
                        @if($customer->telefono)
                            <div class="table-sub-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.09 6.09l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                {{ $customer->telefono }}
                            </div>
                        @endif
                        @if(!$customer->email && !$customer->telefono)
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </td>
                    <td>
                        @if($customer->full_address)
                            <span style="font-size:.85rem;color:#374151;">{{ $customer->full_address }}</span>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </td>
                    <td>
                        @if($customer->origine)
                            <span class="badge-origine">{{ $customer->origine }}</span>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:.35rem;justify-content:flex-end;">
                            <a href="{{ route('backend.customers.show', $customer) }}" class="btn btn-secondary btn-sm" title="Dettaglio">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                            <a href="{{ route('backend.customers.edit', $customer) }}" class="btn btn-secondary btn-sm" title="Modifica">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('backend.customers.destroy', $customer) }}"
                                  onsubmit="return confirm('Eliminare il cliente \'{{ addslashes($customer->full_name) }}\'?')"
                                  style="margin:0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Elimina">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6"/><path d="M14 11v6"/>
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        {{ $customers->links() }}
    </div>
@endif

@endsection
