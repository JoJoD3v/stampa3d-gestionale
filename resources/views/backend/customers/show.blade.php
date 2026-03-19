@extends('layouts.backend')

@section('title', $customer->full_name)
@section('page-title', 'Dettaglio Cliente')

@push('styles')
<style>
.customer-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}
.info-row {
    display: flex;
    align-items: flex-start;
    gap: .85rem;
    padding: .85rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.info-row:last-child { border-bottom: none; }
.info-row-icon { width: 34px; height: 34px; background: #eef1f8; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #023059; }
.info-row-label { font-size: .78rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-bottom: .15rem; }
.info-row-value { font-size: .95rem; color: #0D0D0D; font-weight: 500; }
.customer-avatar {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, #023059, #1a5f9e);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.customer-header-row {
    display: flex; align-items: center; gap: 1.25rem;
    margin-bottom: 1.25rem;
}
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>{{ $customer->full_name }}</h1>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('backend.customers.edit', $customer) }}" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Modifica
        </a>
        <a href="{{ route('backend.customers.index') }}" class="btn btn-secondary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Lista
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="customer-detail-grid">

    {{-- LEFT: Anagrafica + Contatti --}}
    <div class="card">
        <div class="card-header"><h2>Anagrafica</h2></div>
        <div class="card-body">

            <div class="customer-header-row">
                <div class="customer-avatar">
                    {{ strtoupper(mb_substr($customer->nome, 0, 1)) }}{{ strtoupper(mb_substr($customer->cognome, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:1.2rem;font-weight:700;color:#0D0D0D;">{{ $customer->full_name }}</div>
                    @if($customer->origine)
                        <span class="badge-origine" style="margin-top:.3rem;display:inline-block;">{{ $customer->origine }}</span>
                    @endif
                </div>
            </div>

            <div class="info-row">
                <div class="info-row-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <div>
                    <div class="info-row-label">Email</div>
                    <div class="info-row-value">
                        @if($customer->email)
                            <a href="mailto:{{ $customer->email }}" style="color:#023059;">{{ $customer->email }}</a>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-row-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.09 6.09l.97-.97a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                </div>
                <div>
                    <div class="info-row-label">Telefono</div>
                    <div class="info-row-value">
                        @if($customer->telefono)
                            <a href="tel:{{ $customer->telefono }}" style="color:#023059;">{{ $customer->telefono }}</a>
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-row-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <div>
                    <div class="info-row-label">Indirizzo</div>
                    <div class="info-row-value">
                        @if($customer->full_address)
                            {{ $customer->full_address }}
                        @else
                            <span style="color:#9ca3af">—</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT: Note + Azioni --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        <div class="card">
            <div class="card-header"><h2>Note</h2></div>
            <div class="card-body">
                @if($customer->note)
                    <p style="font-size:.92rem;color:#374151;line-height:1.6;white-space:pre-wrap;margin:0;">{{ $customer->note }}</p>
                @else
                    <p style="color:#9ca3af;font-size:.88rem;margin:0;">Nessuna nota.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2>Azioni</h2></div>
            <div class="card-body" style="display:flex;gap:.75rem;flex-wrap:wrap;">
                <a href="{{ route('backend.customers.edit', $customer) }}" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Modifica Cliente
                </a>
                <form method="POST" action="{{ route('backend.customers.destroy', $customer) }}"
                      onsubmit="return confirm('Eliminare definitivamente {{ addslashes($customer->full_name) }}?')"
                      style="margin:0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6"/><path d="M14 11v6"/>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                        Elimina Cliente
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
