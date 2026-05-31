@extends('juge.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Décision judiciaire</h4>
            <small class="text-muted">
                Audience du {{ \Carbon\Carbon::parse($audience->date_audience)->format('d/m/Y') }}
                &mdash; {{ $dossier->numero_registre }}
            </small>
        </div>
        <a href="{{ route('audiences.show', $audience) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if($existingDecision)
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-info-circle"></i>
        <div>Une décision a déjà été rendue pour ce dossier. Soumettre le formulaire la <strong>remplacera</strong>.</div>
    </div>
    @endif

    <div class="row">
        {{-- Recap dossier --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Dossier</h6></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-5">Numéro</dt>
                        <dd class="col-7">{{ $dossier->numero_registre }}</dd>
                        <dt class="col-5">Registre</dt>
                        <dd class="col-7">{{ $dossier->registre->nom ?? '-' }}</dd>
                        <dt class="col-5">Infraction</dt>
                        <dd class="col-7">{{ $dossier->nature_infraction ?: '-' }}</dd>
                        <dt class="col-5">Statut</dt>
                        <dd class="col-7"><span class="badge bg-warning text-dark">{{ $dossier->statut }}</span></dd>
                    </dl>
                </div>
            </div>

            @if($audience->pv)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0">Extrait du PV</h6></div>
                <div class="card-body small text-muted" style="max-height:200px;overflow-y:auto;white-space:pre-wrap;">{{ $audience->pv }}</div>
            </div>
            @endif
        </div>

        {{-- Formulaire décision --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0">Formulaire de décision</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('decisions.store', [$audience, $dossier]) }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type de décision <span class="text-danger">*</span></label>
                                <select name="type_decision" class="form-select @error('type_decision') is-invalid @enderror">
                                    <option value="">-- Choisir --</option>
                                    @foreach(['jugement' => 'Jugement', 'ordonnance' => 'Ordonnance', 'arret' => 'Arrêt'] as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ old('type_decision', $existingDecision?->type_decision) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('type_decision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date de la décision <span class="text-danger">*</span></label>
                                <input type="date" name="date_decision"
                                    class="form-control @error('date_decision') is-invalid @enderror"
                                    value="{{ old('date_decision', $existingDecision?->date_decision ?? date('Y-m-d')) }}">
                                @error('date_decision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Contenu de la décision <span class="text-danger">*</span>
                            </label>
                            <textarea name="contenu" rows="10"
                                class="form-control @error('contenu') is-invalid @enderror"
                                placeholder="Rédiger le dispositif du jugement / de l'ordonnance / de l'arrêt...">{{ old('contenu', $existingDecision?->contenu) }}</textarea>
                            @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-gavel"></i> Rendre la décision
                            </button>
                            <a href="{{ route('audiences.show', $audience) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
