@extends('layouts.backend')

@section('title', 'Nuovo Cliente')
@section('page-title', 'Nuovo Cliente')

@section('content')

<div class="page-header">
    <h1>Nuovo Cliente</h1>
    <a href="{{ route('backend.customers.index') }}" class="btn btn-secondary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Lista
    </a>
</div>

<form method="POST" action="{{ route('backend.customers.store') }}" id="customerForm">
    @csrf

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

        {{-- LEFT: Anagrafica --}}
        <div class="card">
            <div class="card-header"><h2>Anagrafica</h2></div>
            <div class="card-body">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="nome">Nome <span style="color:#BF1111">*</span></label>
                        <input type="text" id="nome" name="nome"
                               class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" required autofocus>
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="cognome">Cognome</label>
                        <input type="text" id="cognome" name="cognome"
                               class="form-control @error('cognome') is-invalid @enderror"
                               value="{{ old('cognome') }}">
                        @error('cognome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="esempio@email.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="telefono">Telefono</label>
                    <input type="text" id="telefono" name="telefono"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono') }}" placeholder="+39 000 0000000">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="origine">Origine</label>
                    <input type="text" id="origine" name="origine" list="origini-list"
                           class="form-control @error('origine') is-invalid @enderror"
                           value="{{ old('origine') }}" placeholder="es. Privato, Ecommerce, Vintend…">
                    <datalist id="origini-list">
                        <option value="Privato">
                        <option value="Ecommerce">
                        <option value="Vintend">
                        <option value="Passaparola">
                        <option value="Social">
                    </datalist>
                    @error('origine')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- RIGHT: Indirizzo + Note --}}
        <div style="display:flex;flex-direction:column;gap:1.5rem;">

            <div class="card">
                <div class="card-header"><h2>Indirizzo</h2></div>
                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label" for="indirizzo">Via / Indirizzo</label>
                        <input type="text" id="indirizzo" name="indirizzo"
                               class="form-control @error('indirizzo') is-invalid @enderror"
                               value="{{ old('indirizzo') }}" placeholder="Via Roma, 1">
                        @error('indirizzo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:grid;grid-template-columns:80px 1fr 60px;gap:.75rem;">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="cap">CAP</label>
                            <input type="text" id="cap" name="cap"
                                   class="form-control @error('cap') is-invalid @enderror"
                                   value="{{ old('cap') }}" placeholder="00100" maxlength="10">
                            @error('cap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="citta">Città</label>
                            <input type="text" id="citta" name="citta"
                                   class="form-control @error('citta') is-invalid @enderror"
                                   value="{{ old('citta') }}" placeholder="Roma">
                            @error('citta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="provincia">Prov.</label>
                            <input type="text" id="provincia" name="provincia"
                                   class="form-control @error('provincia') is-invalid @enderror"
                                   value="{{ old('provincia') }}" placeholder="RM" maxlength="5"
                                   style="text-transform:uppercase">
                            @error('provincia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="card" style="flex:1">
                <div class="card-header"><h2>Note</h2></div>
                <div class="card-body">
                    <div class="form-group" style="margin-bottom:0">
                        <textarea id="note" name="note" rows="5"
                                  class="form-control @error('note') is-invalid @enderror"
                                  placeholder="Informazioni aggiuntive sul cliente…">{{ old('note') }}</textarea>
                        @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div style="margin-top:1.5rem;display:flex;gap:.75rem;justify-content:flex-end;">
        <a href="{{ route('backend.customers.index') }}" class="btn btn-secondary">Annulla</a>
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            Salva Cliente
        </button>
    </div>

</form>

@endsection
