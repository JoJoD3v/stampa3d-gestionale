@extends('layouts.backend')

@section('title', $project->name)
@section('page-title', 'Dettaglio Progetto')

@push('styles')
<style>
.project-detail-grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 1.5rem;
    align-items: start;
}
.project-photo-card { border-radius: 12px; overflow: hidden; background: #fff; border: 1px solid #e5e7eb; }
.project-photo-card img { width: 100%; display: block; max-height: 320px; object-fit: cover; }
.project-photo-placeholder {
    width: 100%; aspect-ratio: 4/3; background: #f1f5f9;
    display: flex; align-items: center; justify-content: center; color: #c0c9d8;
}
.info-row {
    display: flex; align-items: flex-start; gap: 1rem;
    padding: .85rem 0; border-bottom: 1px solid #f1f5f9;
}
.info-row:last-child { border-bottom: none; }
.info-row-icon { width: 34px; height: 34px; background: #eef1f8; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #023059; }
.info-row-label { font-size: .78rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-bottom: .15rem; }
.info-row-value { font-size: .95rem; color: #0D0D0D; font-weight: 500; }
.download-file-item {
    display: flex; align-items: center; gap: .75rem;
    padding: .75rem 1rem; border-radius: 8px;
    border: 1px solid #e5e7eb; background: #fafbfc;
    transition: border-color .15s, background .15s;
    margin-bottom: .5rem;
}
.download-file-item:hover { border-color: #023059; background: #f0f4fb; }
.download-file-item .file-icon { width: 38px; height: 38px; background: #eef1f8; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.download-file-item .file-info { flex: 1; min-width: 0; }
.download-file-item .file-info strong { display: block; font-size: .88rem; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.download-file-item .file-info small { color: #9ca3af; font-size: .78rem; }
.download-file-item .file-ext { font-size: .7rem; font-weight: 700; background: #023059; color: #fff; border-radius: 3px; padding: 2px 6px; flex-shrink: 0; }
.download-btn {
    display: flex; align-items: center; gap: .35rem;
    background: #023059; color: #fff; border: none; border-radius: 6px;
    padding: .45rem .9rem; font-size: .82rem; font-weight: 600;
    cursor: pointer; text-decoration: none; flex-shrink: 0;
    transition: background .15s;
}
.download-btn:hover { background: #01213f; color: #fff; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>{{ $project->name }}</h1>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('backend.projects.edit', $project) }}" class="btn btn-secondary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Modifica
        </a>
        <a href="{{ route('backend.projects.index') }}" class="btn btn-secondary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Lista
        </a>
        <form method="POST" action="{{ route('backend.projects.destroy', $project) }}"
              onsubmit="return confirm('Eliminare il progetto \'{{ addslashes($project->name) }}\'?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                </svg>
                Elimina
            </button>
        </form>
    </div>
</div>

<div class="project-detail-grid">

    {{-- LEFT: Photo --}}
    <div>
        <div class="project-photo-card">
            @if($project->photo_url)
                <img src="{{ $project->photo_url }}" alt="{{ $project->name }}">
            @else
                <div class="project-photo-placeholder">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
            @endif
        </div>

        <div style="margin-top:1rem;padding:.5rem 0;font-size:.78rem;color:#9ca3af;text-align:center;">
            Creato il {{ $project->created_at->format('d/m/Y \a\l\l\e H:i') }}
        </div>
    </div>

    {{-- RIGHT: Info + Files --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Details card --}}
        <div class="card">
            <div class="card-header"><h2>Informazioni Progetto</h2></div>
            <div class="card-body">

                <div class="info-row">
                    <div class="info-row-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-row-label">Nome Progetto</div>
                        <div class="info-row-value">{{ $project->name }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-row-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-row-label">Tipo Filo</div>
                        <div class="info-row-value">{{ $project->filament_type }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-row-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-row-label">Grammi Filo</div>
                        <div class="info-row-value">{{ $project->filament_grams }} g</div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-row-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-row-label">Tempo di Stampa</div>
                        <div class="info-row-value">
                            @if($project->print_hours > 0)
                                {{ $project->print_hours }} {{ $project->print_hours === 1 ? 'ora' : 'ore' }}
                                @if($project->print_minutes > 0) e @endif
                            @endif
                            @if($project->print_minutes > 0)
                                {{ $project->print_minutes }} minuti
                            @endif
                            @if($project->print_hours === 0 && $project->print_minutes === 0)
                                <span style="color:#9ca3af">—</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($project->height_cm || $project->width_cm || $project->depth_cm)
                <div class="info-row">
                    <div class="info-row-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-row-label">Dimensioni Oggetto</div>
                        <div class="info-row-value">
                            {{ $project->height_cm ? number_format($project->height_cm, 2) . ' cm' : '—' }}
                            &nbsp;×&nbsp;
                            {{ $project->width_cm ? number_format($project->width_cm, 2) . ' cm' : '—' }}
                            &nbsp;×&nbsp;
                            {{ $project->depth_cm ? number_format($project->depth_cm, 2) . ' cm' : '—' }}
                            <span style="font-size:.78rem;color:#9ca3af;margin-left:.25rem;">(A × L × P)</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Files for download --}}
        <div class="card">
            <div class="card-header">
                <h2>
                    File Progetto
                    <span style="font-weight:400;color:#6b7280;font-size:.85rem;">({{ $project->files->count() }} {{ $project->files->count() === 1 ? 'file' : 'files' }})</span>
                </h2>
            </div>
            <div class="card-body">
                @if($project->files->isEmpty())
                    <p style="color:#9ca3af;font-size:.88rem;text-align:center;padding:1rem 0;">
                        Nessun file caricato per questo progetto.
                    </p>
                @else
                    @foreach($project->files as $file)
                    <div class="download-file-item">
                        <div class="file-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#023059" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div class="file-info">
                            <strong title="{{ $file->original_name }}">{{ $file->original_name }}</strong>
                            <small>{{ $file->file_size_formatted }} &bull; Caricato il {{ $file->created_at->format('d/m/Y') }}</small>
                        </div>
                        <span class="file-ext">{{ $file->extension }}</span>
                        <a href="{{ route('backend.projects.files.download', [$project, $file]) }}"
                           class="download-btn">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Scarica
                        </a>
                    </div>
                    @endforeach
                @endif

                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;text-align:right;">
                    <a href="{{ route('backend.projects.edit', $project) }}" class="btn btn-secondary btn-sm">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Aggiungi file
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
