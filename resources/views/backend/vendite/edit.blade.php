@extends('layouts.backend')

@section('title', 'Modifica Vendita')
@section('page-title', 'Modifica Vendita')

@section('content')

<div class="page-header">
    <h1>Modifica Vendita</h1>
    <a href="{{ route('backend.vendite.index') }}" class="btn btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Torna alla Lista
    </a>
</div>

<form method="POST" action="{{ route('backend.vendite.update', $vendita) }}">
    @csrf
    @method('PUT')

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
                                {{ old('project_id', $vendita->project_id) == $project->id ? 'selected' : '' }}>
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
                           value="{{ old('importo', $vendita->importo) }}" min="0.01" step="0.01" required>
                    @error('importo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="data_vendita">Data vendita <span style="color:#BF1111">*</span></label>
                    <input type="date" id="data_vendita" name="data_vendita"
                           class="form-control @error('data_vendita') is-invalid @enderror"
                           value="{{ old('data_vendita', $vendita->data_vendita->format('Y-m-d')) }}" required>
                    @error('data_vendita')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="note">Note <span style="color:#9ca3af;font-weight:400;">(opzionale)</span></label>
                    <textarea id="note" name="note" rows="3"
                              class="form-control @error('note') is-invalid @enderror"
                              placeholder="Eventuali note sulla vendita…">{{ old('note', $vendita->note) }}</textarea>
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;gap:.75rem;margin-top:1.5rem;align-items:center;flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">Aggiorna vendita</button>
                    <a href="{{ route('backend.vendite.index') }}" class="btn btn-secondary">Annulla</a>
                    <form method="POST" action="{{ route('backend.vendite.destroy', $vendita) }}"
                          onsubmit="return confirm('Eliminare questa vendita?')" style="margin-left:auto;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14H6L5 6"/>
                                <path d="M10 11v6M14 11v6"/>
                                <path d="M9 6V4h6v2"/>
                            </svg>
                            Elimina
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</form>

@endsection
