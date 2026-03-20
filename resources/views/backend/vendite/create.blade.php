@extends('layouts.backend')

@section('title', 'Nuova Vendita')
@section('page-title', 'Nuova Vendita')

@section('content')

<div class="page-header">
    <h1>Nuova Vendita</h1>
    <a href="{{ route('backend.vendite.index') }}" class="btn btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Torna alla Lista
    </a>
</div>

<form method="POST" action="{{ route('backend.vendite.store') }}">
    @csrf

    <div style="max-width:560px;">
        <div class="card">
            <div class="card-header"><h2>Dati Vendita</h2></div>
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label" for="project_id">Progetto <span style="color:#BF1111">*</span></label>
                    <select id="project_id" name="project_id"
                            class="form-control @error('project_id') is-invalid @enderror" required>
                        <option value="">— Seleziona progetto —</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="importo">Somma vendita (€) <span style="color:#BF1111">*</span></label>
                    <input type="number" id="importo" name="importo"
                           class="form-control @error('importo') is-invalid @enderror"
                           value="{{ old('importo') }}" min="0.01" step="0.01"
                           placeholder="Es. 25.00" required>
                    @error('importo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="data_vendita">Data vendita <span style="color:#BF1111">*</span></label>
                    <input type="date" id="data_vendita" name="data_vendita"
                           class="form-control @error('data_vendita') is-invalid @enderror"
                           value="{{ old('data_vendita', date('Y-m-d')) }}" required>
                    @error('data_vendita')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="note">Note <span style="color:#9ca3af;font-weight:400;">(opzionale)</span></label>
                    <textarea id="note" name="note" rows="3"
                              class="form-control @error('note') is-invalid @enderror"
                              placeholder="Eventuali note sulla vendita…">{{ old('note') }}</textarea>
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                    <button type="submit" class="btn btn-primary">Salva vendita</button>
                    <a href="{{ route('backend.vendite.index') }}" class="btn btn-secondary">Annulla</a>
                </div>

            </div>
        </div>
    </div>

</form>

@endsection
