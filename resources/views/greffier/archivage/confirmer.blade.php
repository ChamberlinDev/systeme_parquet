@extends('greffier.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Archiver le dossier</h4>
            <small class="text-muted">{{ $dossier->numero_rp }} — {{ $dossier->numero_registre }}</small>
        </div>
        <a href="{{ route('dossiers.show', $dossier) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="alert alert-warning d-flex gap-2 align-items-start mb-4">
                <i class="fas fa-exclamation-triangle mt-1"></i>
                <div>
                    <strong>Action irréversible.</strong> Une fois archivé, le dossier
                    <strong>{{ $dossier->numero_registre }}</strong> ne pourra plus être modifié.
                    Il restera consultable dans la liste des archives.
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white"><h6 class="mb-0">Récapitulatif</h6></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-4">Numéro RP</dt>
                        <dd class="col-8">{{ $dossier->numero_rp }}</dd>
                        <dt class="col-4">Registre</dt>
                        <dd class="col-8">{{ $dossier->registre->nom ?? '-' }}</dd>
                        <dt class="col-4">Statut actuel</dt>
                        <dd class="col-8"><span class="badge bg-info">{{ $dossier->statut }}</span></dd>
                        <dt class="col-4">Infraction</dt>
                        <dd class="col-8">{{ $dossier->nature_infraction ?: '—' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0">Formulaire d'archivage</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('archivage.store', $dossier) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Date d'archivage <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="date_archivage"
                                class="form-control @error('date_archivage') is-invalid @enderror"
                                value="{{ old('date_archivage', date('Y-m-d')) }}">
                            @error('date_archivage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Motif d'archivage <span class="text-danger">*</span>
                            </label>
                            <textarea name="motif_archivage" rows="4"
                                class="form-control @error('motif_archivage') is-invalid @enderror"
                                placeholder="Préciser la raison de l'archivage (exécution complète, délai écoulé, etc.)...">{{ old('motif_archivage') }}</textarea>
                            @error('motif_archivage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-archive"></i> Confirmer l'archivage
                            </button>
                            <a href="{{ route('dossiers.show', $dossier) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
