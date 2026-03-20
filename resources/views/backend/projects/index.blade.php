@extends('layouts.backend')

@section('title', 'Gestione Progetti')
@section('page-title', 'Gestione Progetti')

@section('content')

<div class="page-header">
    <h1>Progetti di Stampa</h1>
    <a href="{{ route('backend.projects.create') }}" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nuovo Progetto
    </a>
</div>

<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:.9rem 1.25rem;">
        <form method="GET" action="{{ route('backend.projects.index') }}"
              style="display:flex;gap:.65rem;align-items:center;flex-wrap:wrap;">

            {{-- Search input --}}
            <div style="position:relative;flex:1;min-width:200px;max-width:420px;">
                <svg style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#9ca3af;"
                     width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" id="search" value="{{ $search }}"
                       placeholder="Cerca progetto per nome…"
                       autocomplete="off"
                       style="width:100%;padding:.55rem 1rem .55rem 2.35rem;border:1.5px solid #e4e8f0;border-radius:8px;font-size:.88rem;color:#374151;background:#fff;outline:none;transition:border-color .15s;"
                       onfocus="this.style.borderColor='#023059'" onblur="this.style.borderColor='#e4e8f0'">
            </div>

            {{-- Buttons --}}
            <button type="submit" class="btn btn-primary" style="padding:.52rem 1.2rem;">
                Cerca
            </button>
            @if($search)
            <a href="{{ route('backend.projects.index') }}"
               style="padding:.52rem .9rem;background:#f3f4f6;color:#6b7280;border-radius:8px;font-size:.85rem;border:1.5px solid #e4e8f0;white-space:nowrap;">
                Reimposta
            </a>
            @endif

            {{-- Result count --}}
            <span style="margin-left:auto;font-size:.82rem;color:#9ca3af;white-space:nowrap;">
                @if($search)
                    {{ $projects->total() }} {{ $projects->total() === 1 ? 'risultato' : 'risultati' }}
                    per <em style="color:#374151;font-style:normal;font-weight:600;">"{{ $search }}"</em>
                @else
                    {{ $projects->total() }} {{ $projects->total() === 1 ? 'progetto' : 'progetti' }} totali
                @endif
            </span>

        </form>
    </div>
</div>

@if($projects->isEmpty())
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
        <p>Nessun progetto trovato.<br>
            <a href="{{ route('backend.projects.create') }}" style="color:#023059;font-weight:700;">Crea il primo progetto</a>
        </p>
    </div>
@else
    <div class="projects-grid">
        @foreach($projects as $project)
        <div class="project-card">
            {{-- Clickable area: photo + body --}}
            <a href="{{ route('backend.projects.show', $project) }}" class="project-card-link">
            <div class="project-card-photo">
                @if($project->photo_url)
                    <img src="{{ $project->photo_url }}" alt="{{ $project->name }}">
                @else
                    <div class="project-card-photo-placeholder">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Body --}}
            <div class="project-card-body">
                <div class="project-card-title">{{ $project->name }}</div>

                <div class="project-card-meta">
                    <div class="project-meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2"/><path d="M12 6v6l4 2"/>
                        </svg>
                        {{ $project->print_time }}
                    </div>
                    <div class="project-meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                        {{ $project->filament_type }} &mdash; {{ $project->filament_grams }}g
                    </div>
                    <div class="project-meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        {{ $project->files_count }} {{ $project->files_count === 1 ? 'file' : 'files' }}
                    </div>
                    @if($project->height_cm || $project->width_cm || $project->depth_cm)
                    <div class="project-meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                        {{ $project->height_cm ? number_format($project->height_cm, 1) : '—' }}
                        &times;
                        {{ $project->width_cm ? number_format($project->width_cm, 1) : '—' }}
                        &times;
                        {{ $project->depth_cm ? number_format($project->depth_cm, 1) : '—' }} cm
                    </div>
                    @endif
                </div>
            </div>
            </a>{{-- end .project-card-link --}}

            {{-- Actions --}}
            <div class="project-card-actions">
                <a href="{{ route('backend.projects.show', $project) }}" class="btn btn-secondary btn-sm" title="Dettaglio">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    Dettaglio
                </a>
                <a href="{{ route('backend.projects.edit', $project) }}" class="btn btn-secondary btn-sm" title="Modifica">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Modifica
                </a>
                <form method="POST" action="{{ route('backend.projects.destroy', $project) }}"
                      onsubmit="return confirm('Eliminare il progetto \'{{ addslashes($project->name) }}\'? Tutti i file allegati saranno eliminati.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6"/><path d="M14 11v6"/>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                        Elimina
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:1.5rem;">
        {{ $projects->links('vendor.pagination.custom') }}
    </div>
@endif

@endsection
