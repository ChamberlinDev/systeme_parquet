@extends('greffier.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Planifier une audience</h4>
        <a href="{{ route('audiences.index.greffier') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('audiences.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date de l'audience <span class="text-danger">*</span></label>
                                <input type="date" name="date_audience" class="form-control @error('date_audience') is-invalid @enderror"
                                    value="{{ old('date_audience') }}">
                                @error('date_audience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Salle <span class="text-danger">*</span></label>
                                <input type="text" name="salle" class="form-control @error('salle') is-invalid @enderror"
                                    value="{{ old('salle') }}" placeholder="Ex: Salle A, Chambre correctionnelle 1">
                                @error('salle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type d'audience <span class="text-danger">*</span></label>
                            <select name="type_audience" class="form-select @error('type_audience') is-invalid @enderror">
                                <option value="">-- Choisir --</option>
                                <option value="correctionnelle" {{ old('type_audience') === 'correctionnelle' ? 'selected' : '' }}>Correctionnelle</option>
                                <option value="criminelle" {{ old('type_audience') === 'criminelle' ? 'selected' : '' }}>Criminelle</option>
                                <option value="sociale" {{ old('type_audience') === 'sociale' ? 'selected' : '' }}>Sociale</option>
                                <option value="refere" {{ old('type_audience') === 'refere' ? 'selected' : '' }}>Référé</option>
                            </select>
                            @error('type_audience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rôle (observations)</label>
                            <textarea name="role" rows="2" class="form-control"
                                placeholder="Notes sur le rôle de l'audience...">{{ old('role') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Dossiers inscrits au rôle <span class="text-danger">*</span>
                                <small class="text-muted fw-normal">(sélection multiple)</small>
                            </label>
                            @error('dossier_ids')
                                <div class="text-danger small mb-1">{{ $message }}</div>
                            @enderror
                            <div class="border rounded" style="max-height: 320px; overflow-y: auto;">
                                @forelse($dossiers as $dossier)
                                <label class="d-flex align-items-start gap-3 px-3 py-2 border-bottom hover-bg"
                                    style="cursor:pointer">
                                    <input type="checkbox" name="dossier_ids[]" value="{{ $dossier->id_dossier }}"
                                        class="form-check-input mt-1"
                                        {{ in_array($dossier->id_dossier, old('dossier_ids', [])) ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-semibold small">{{ $dossier->numero_registre }}</div>
                                        <div class="text-muted" style="font-size:0.8rem">
                                            {{ $dossier->registre->nom ?? '-' }} &mdash;
                                            {{ $dossier->nature_infraction ?: 'Infraction non précisée' }}
                                        </div>
                                        <span class="badge bg-warning text-dark" style="font-size:0.7rem">{{ $dossier->statut }}</span>
                                    </div>
                                </label>
                                @empty
                                <div class="text-muted text-center py-4">Aucun dossier disponible</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check"></i> Planifier
                            </button>
                            <a href="{{ route('audiences.index.greffier') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body small text-muted">
                    <h6 class="text-dark fw-semibold mb-2">Guide</h6>
                    <ul class="ps-3 mb-0">
                        <li>Sélectionnez au moins un dossier pour le rôle.</li>
                        <li>Les dossiers classés ou archivés ne sont pas listés.</li>
                        <li>Le PV et les décisions seront saisis après l'audience.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
