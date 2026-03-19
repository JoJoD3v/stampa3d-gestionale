@extends('layouts.backend')

@section('title', 'Nuova Stampante')
@section('page-title', 'Nuova Stampante')

@section('content')

<div class="page-header">
    <h1>Aggiungi Stampante</h1>
    <a href="{{ route('backend.printers.index') }}" class="btn btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Torna alla Lista
    </a>
</div>

<div class="card" style="max-width: 560px;">
    <div class="card-header">
        <h2>Dati Stampante</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.printers.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nome Stampante <span style="color:#BF1111">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Es. Stampante Sala A"
                    required
                    autofocus
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="model">Modello <span style="color:#BF1111">*</span></label>
                <input
                    type="text"
                    id="model"
                    name="model"
                    class="form-control @error('model') is-invalid @enderror"
                    value="{{ old('model') }}"
                    placeholder="Es. Creality Ender 3 Pro"
                    required
                >
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 0.5rem;">
                <p style="font-size:0.82rem; color:#6b7280;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:3px;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Lo status iniziale sarà impostato automaticamente a <strong>Spenta</strong>.
                </p>
            </div>

            <hr class="divider">

            <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                <a href="{{ route('backend.printers.index') }}" class="btn btn-secondary">Annulla</a>
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Aggiungi Stampante
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
