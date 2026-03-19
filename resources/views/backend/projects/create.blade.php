@extends('layouts.backend')

@section('title', 'Nuovo Progetto')
@section('page-title', 'Nuovo Progetto')

@push('styles')
<style>
.drop-zone {
    border: 2px dashed #B4C0D9;
    border-radius: 10px;
    padding: 2rem 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    background: #f8f9fc;
}
.drop-zone.dragover {
    border-color: #023059;
    background: #eef1f8;
}
.drop-zone svg { opacity: .4; margin-bottom: .5rem; }
.drop-zone p { margin: 0; font-size: .88rem; color: #6b7280; }
.drop-zone p strong { color: #023059; }
.file-list { margin-top: 1rem; display: flex; flex-direction: column; gap: .4rem; }
.file-list-item {
    display: flex; align-items: center; gap: .6rem;
    background: #f1f5f9; border-radius: 6px;
    padding: .45rem .75rem; font-size: .83rem; color: #374151;
}
.file-list-item svg { flex-shrink: 0; opacity: .5; }
.file-list-item span { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.file-list-item .file-size { color: #9ca3af; flex-shrink: 0; font-size: .78rem; }
.file-list-item .remove-file {
    background: none; border: none; cursor: pointer;
    color: #BF1111; padding: 0 2px; line-height: 1;
    opacity: .7; transition: opacity .15s;
}
.file-list-item .remove-file:hover { opacity: 1; }
.photo-preview { margin-top: .8rem; }
.photo-preview img { max-height: 140px; border-radius: 8px; border: 1px solid #e5e7eb; }
.time-row { display: flex; gap: 1rem; }
.time-row .form-group { flex: 1; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Nuovo Progetto</h1>
    <a href="{{ route('backend.projects.index') }}" class="btn btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Torna alla Lista
    </a>
</div>

<form method="POST" action="{{ route('backend.projects.store') }}" enctype="multipart/form-data" id="projectForm">
    @csrf

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

        {{-- LEFT COLUMN --}}
        <div>
            <div class="card">
                <div class="card-header"><h2>Dati Progetto</h2></div>
                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label" for="name">Nome Progetto <span style="color:#BF1111">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Es. Supporto per monitor" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="filament_type">Tipo Filo <span style="color:#BF1111">*</span></label>
                        <input type="text" id="filament_type" name="filament_type"
                               class="form-control @error('filament_type') is-invalid @enderror"
                               value="{{ old('filament_type') }}" placeholder="Es. PLA, PETG, ABS..." required>
                        @error('filament_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="filament_grams">Grammi Filo <span style="color:#BF1111">*</span></label>
                        <input type="number" id="filament_grams" name="filament_grams"
                               class="form-control @error('filament_grams') is-invalid @enderror"
                               value="{{ old('filament_grams') }}" placeholder="Es. 120" min="1" required>
                        @error('filament_grams')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tempo di Stampa <span style="color:#BF1111">*</span></label>
                        <div class="time-row">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label" for="print_hours" style="font-size:.8rem;color:#6b7280;margin-bottom:.25rem;">Ore</label>
                                <input type="number" id="print_hours" name="print_hours"
                                       class="form-control @error('print_hours') is-invalid @enderror"
                                       value="{{ old('print_hours', 0) }}" min="0" required>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label" for="print_minutes" style="font-size:.8rem;color:#6b7280;margin-bottom:.25rem;">Minuti</label>
                                <input type="number" id="print_minutes" name="print_minutes"
                                       class="form-control @error('print_minutes') is-invalid @enderror"
                                       value="{{ old('print_minutes', 0) }}" min="0" max="59" required>
                            </div>
                        </div>
                        @error('print_hours')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                        @error('print_minutes')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:.3rem;">
                        <label class="form-label">Dimensioni Oggetto (cm)</label>
                        <div class="time-row">
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label" for="height_cm" style="font-size:.8rem;color:#6b7280;margin-bottom:.25rem;">Altezza</label>
                                <input type="number" id="height_cm" name="height_cm" step="0.01"
                                       class="form-control @error('height_cm') is-invalid @enderror"
                                       value="{{ old('height_cm') }}" placeholder="0.00" min="0">
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label" for="width_cm" style="font-size:.8rem;color:#6b7280;margin-bottom:.25rem;">Larghezza</label>
                                <input type="number" id="width_cm" name="width_cm" step="0.01"
                                       class="form-control @error('width_cm') is-invalid @enderror"
                                       value="{{ old('width_cm') }}" placeholder="0.00" min="0">
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label class="form-label" for="depth_cm" style="font-size:.8rem;color:#6b7280;margin-bottom:.25rem;">Profondità</label>
                                <input type="number" id="depth_cm" name="depth_cm" step="0.01"
                                       class="form-control @error('depth_cm') is-invalid @enderror"
                                       value="{{ old('depth_cm') }}" placeholder="0.00" min="0">
                            </div>
                        </div>
                        @error('height_cm')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                        @error('width_cm')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                        @error('depth_cm')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:1.5rem;">

            {{-- Photo Upload --}}
            <div class="card">
                <div class="card-header"><h2>Foto Progetto</h2></div>
                <div class="card-body">
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="photo">Immagine (max 5 MB)</label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                               class="form-control @error('photo') is-invalid @enderror"
                               onchange="previewPhoto(this)">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="photo-preview" id="photoPreview" style="display:none;">
                            <img id="photoPreviewImg" src="" alt="Anteprima">
                        </div>
                    </div>
                </div>
            </div>

            {{-- File Upload --}}
            <div class="card" style="flex:1">
                <div class="card-header"><h2>File Progetto</h2></div>
                <div class="card-body">
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">File di Stampa (STL, OBJ, ecc.) — max 100 MB cad.</label>

                        <div class="drop-zone" id="dropZone">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:block;margin:0 auto .5rem;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p><strong>Trascina qui i file</strong> oppure clicca per selezionarli</p>
                            <p style="margin-top:.3rem;font-size:.8rem;">Supporta upload multipli</p>
                        </div>

                        <input type="file" id="filesInput" name="files[]" multiple
                               style="display:none"
                               onchange="handleFileSelect(this.files)">

                        @error('files')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                        @error('files.*')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror

                        <div class="file-list" id="fileList"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div style="margin-top:1.5rem;display:flex;gap:.75rem;justify-content:flex-end;">
        <a href="{{ route('backend.projects.index') }}" class="btn btn-secondary">Annulla</a>
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            Salva Progetto
        </button>
    </div>

</form>

@endsection

@push('scripts')
<script>
// ── Photo preview ─────────────────────────────────────
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    const img     = document.getElementById('photoPreviewImg');
    if (input.files && input.files[0]) {
        img.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
    }
}

// ── Drag & Drop ──────────────────────────────────────
const dropZone  = document.getElementById('dropZone');
const fileInput = document.getElementById('filesInput');
let   filesDT   = new DataTransfer(); // accumulate files

dropZone.addEventListener('click', () => fileInput.click());

dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    handleFileSelect(e.dataTransfer.files);
});

function handleFileSelect(newFiles) {
    for (const f of newFiles) filesDT.items.add(f);
    fileInput.files = filesDT.files;
    renderFileList();
}

function removeFile(index) {
    const newDT = new DataTransfer();
    const files = [...filesDT.files];
    files.forEach((f, i) => { if (i !== index) newDT.items.add(f); });
    filesDT = newDT;
    fileInput.files = filesDT.files;
    renderFileList();
}

function renderFileList() {
    const list = document.getElementById('fileList');
    list.innerHTML = '';
    [...filesDT.files].forEach((f, i) => {
        const size = f.size >= 1048576
            ? (f.size / 1048576).toFixed(1) + ' MB'
            : Math.round(f.size / 1024) + ' KB';
        list.innerHTML += `
        <div class="file-list-item">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            <span title="${f.name}">${f.name}</span>
            <span class="file-size">${size}</span>
            <button type="button" class="remove-file" onclick="removeFile(${i})" title="Rimuovi">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>`;
    });
}
</script>
@endpush
