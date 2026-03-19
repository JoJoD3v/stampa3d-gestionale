@extends('layouts.backend')

@section('title', 'Modifica Stampante')
@section('page-title', 'Modifica Stampante')

@section('content')

<div class="page-header">
    <h1>Modifica: {{ $printer->name }}</h1>
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
        <span class="status-badge {{ $printer->status_color }}">{{ $printer->status_label }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.printers.update', $printer) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nome Stampante <span style="color:#BF1111">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $printer->name) }}"
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
                    value="{{ old('model', $printer->model) }}"
                    required
                >
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr class="divider">

            <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                <a href="{{ route('backend.printers.index') }}" class="btn btn-secondary">Annulla</a>
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Salva Modifiche
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
