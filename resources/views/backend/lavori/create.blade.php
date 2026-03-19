@extends('layouts.backend')

@section('title', 'Nuovo Lavoro')
@section('page-title', 'Nuovo Lavoro')

@section('content')

<div class="page-header">
    <h1>Nuovo Lavoro</h1>
    <a href="{{ route('backend.lavori.index') }}" class="btn btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Lista
    </a>
</div>

{{-- Pass project data to JS --}}
<script>
const PROJECTS = @json($projects->map(fn($p) => [
    'id'             => $p->id,
    'name'           => $p->name,
    'filament_grams' => $p->filament_grams ?? 0,
    'print_hours'    => $p->print_hours ?? 0,
    'print_minutes'  => $p->print_minutes ?? 0,
]));
</script>

<form method="POST" action="{{ route('backend.lavori.store') }}" id="lavoroForm">
    @csrf

    <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:1.5rem; align-items:start;">

        {{-- LEFT: Dettagli Lavoro --}}
        <div class="card">
            <div class="card-header"><h2>Dettagli Lavoro</h2></div>
            <div class="card-body">

                <div class="form-group">
                    <label class="form-label" for="customer_id">Cliente <span style="color:#BF1111">*</span></label>
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <select id="customer_id" name="customer_id"
                                class="form-control @error('customer_id') is-invalid @enderror" required style="flex:1">
                            <option value="">— Seleziona cliente —</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->full_name }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('backend.customers.create') }}" class="btn btn-secondary btn-sm" title="Nuovo cliente" target="_blank">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        </a>
                    </div>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="preventivo">Preventivo (€)</label>
                    <input type="number" id="preventivo" name="preventivo" step="0.01" min="0"
                           class="form-control @error('preventivo') is-invalid @enderror"
                           value="{{ old('preventivo') }}" placeholder="0.00">
                    @error('preventivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="scadenza">Scadenza</label>
                    <input type="date" id="scadenza" name="scadenza"
                           class="form-control @error('scadenza') is-invalid @enderror"
                           value="{{ old('scadenza') }}">
                    @error('scadenza')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Stato</label>
                    <select id="status" name="status"
                            class="form-control @error('status') is-invalid @enderror">
                        @foreach(\App\Models\Lavoro::STATUS_LABELS as $val => $label)
                            <option value="{{ $val }}" {{ old('status', 'bozza') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="note">Note</label>
                    <textarea id="note" name="note" rows="4"
                              class="form-control @error('note') is-invalid @enderror"
                              placeholder="Note aggiuntive…">{{ old('note') }}</textarea>
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- RIGHT: Progetti da stampare --}}
        <div class="card">
            <div class="card-header"><h2>Progetti da Stampare</h2></div>
            <div class="card-body">

                @error('righe')
                    <div class="alert alert-danger" style="margin-bottom:1rem;">{{ $message }}</div>
                @enderror

                {{-- Summary bar --}}
                <div class="lavoro-summary">
                    <div>
                        <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Filamento totale</div>
                        <div class="summary-value"><span id="total-filament">0</span> g</div>
                    </div>
                    <div>
                        <div style="font-size:.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Tempo totale</div>
                        <div class="summary-value" id="total-time">0h 0min</div>
                    </div>
                </div>

                {{-- Project rows container --}}
                <div id="righe-container"></div>

                {{-- Add row button --}}
                <button type="button" class="btn btn-secondary" style="width:100%;margin-top:.75rem;" onclick="addRow()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Aggiungi Progetto
                </button>

            </div>
        </div>

    </div>

    <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:.75rem;">
        <a href="{{ route('backend.lavori.index') }}" class="btn btn-secondary">Annulla</a>
        <button type="submit" class="btn btn-primary">Salva Lavoro</button>
    </div>

</form>

<script>
let rowIndex = 0;

function formatMinutes(totalMin) {
    const h = Math.floor(totalMin / 60);
    const m = totalMin % 60;
    return h + 'h ' + m + 'min';
}

function addRow(projectId = null, quantita = 1) {
    const idx = rowIndex++;
    const container = document.getElementById('righe-container');

    const div = document.createElement('div');
    div.className = 'progetto-row';
    div.id = 'row-' + idx;

    // Build project select options
    let options = '<option value="">— Seleziona progetto —</option>';
    PROJECTS.forEach(p => {
        const sel = (p.id == projectId) ? 'selected' : '';
        options += `<option value="${p.id}" ${sel}>${p.name}</option>`;
    });

    div.innerHTML = `
        <select name="righe[${idx}][project_id]" class="form-control" onchange="updateRow(${idx})">
            ${options}
        </select>
        <input type="number" name="righe[${idx}][quantita]" value="${quantita}" min="1"
               class="form-control" style="text-align:center;" oninput="updateRow(${idx})">
        <div id="row-calc-${idx}" style="font-size:.82rem;color:#6b7280;padding-left:.25rem;">—</div>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${idx})" title="Rimuovi">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    `;

    container.appendChild(div);

    if (projectId) updateRow(idx);

    // Show/hide old()-error rows placeholder if any
    const oldRighe = @json(old('righe', []));
}

function removeRow(idx) {
    const el = document.getElementById('row-' + idx);
    if (el) el.remove();
    updateTotals();
}

function updateRow(idx) {
    const select = document.querySelector(`[name="righe[${idx}][project_id]"]`);
    const qtyInput = document.querySelector(`[name="righe[${idx}][quantita]"]`);
    const calc = document.getElementById('row-calc-' + idx);

    if (!select || !qtyInput || !calc) return;

    const pid = parseInt(select.value);
    const qty = parseInt(qtyInput.value) || 1;
    const project = PROJECTS.find(p => p.id === pid);

    if (project) {
        const filament = project.filament_grams * qty;
        const mins = (project.print_hours * 60 + project.print_minutes) * qty;
        calc.textContent = filament + ' g · ' + formatMinutes(mins);
        calc.style.color = '#023059';
    } else {
        calc.textContent = '—';
        calc.style.color = '#9ca3af';
    }

    updateTotals();
}

function updateTotals() {
    let totalFilament = 0;
    let totalMins = 0;

    document.querySelectorAll('#righe-container .progetto-row').forEach(row => {
        const idx = row.id.replace('row-', '');
        const select = document.querySelector(`[name="righe[${idx}][project_id]"]`);
        const qtyInput = document.querySelector(`[name="righe[${idx}][quantita]"]`);

        if (!select || !qtyInput) return;
        const pid = parseInt(select.value);
        const qty = parseInt(qtyInput.value) || 1;
        const project = PROJECTS.find(p => p.id === pid);

        if (project) {
            totalFilament += project.filament_grams * qty;
            totalMins += (project.print_hours * 60 + project.print_minutes) * qty;
        }
    });

    document.getElementById('total-filament').textContent = totalFilament;
    document.getElementById('total-time').textContent = formatMinutes(totalMins);
}

// Restore old() values on validation errors
document.addEventListener('DOMContentLoaded', function () {
    const oldRighe = @json(old('righe', []));
    if (oldRighe && oldRighe.length > 0) {
        oldRighe.forEach(r => addRow(r.project_id, r.quantita));
    } else {
        addRow(); // start with one empty row
    }
});

// Guard: prevent submit with no valid rows
document.getElementById('lavoroForm').addEventListener('submit', function (e) {
    const rows = document.querySelectorAll('#righe-container .progetto-row');
    const valid = Array.from(rows).filter(row => {
        const idx = row.id.replace('row-', '');
        const sel = document.querySelector(`[name="righe[${idx}][project_id]"]`);
        return sel && sel.value !== '';
    });

    if (valid.length === 0) {
        e.preventDefault();
        alert('Aggiungi almeno un progetto al lavoro.');
        return;
    }

    // Remove rows with no project selected before submitting
    rows.forEach(row => {
        const idx = row.id.replace('row-', '');
        const sel = document.querySelector(`[name="righe[${idx}][project_id]"]`);
        if (!sel || sel.value === '') row.remove();
    });
});
</script>

@endsection
