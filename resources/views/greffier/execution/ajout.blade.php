@extends('greffier.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Créer une exécution</h4>
            <small class="text-muted">Dossier {{ $decision->dossier->numero_registre ?? '' }}</small>
        </div>
        <a href="{{ route('dossiers.show', $decision->dossier) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour au dossier
        </a>
    </div>

    <div class="row">
        {{-- Recap décision --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0">Décision concernée</h6></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-5">Dossier</dt>
                        <dd class="col-7">{{ $decision->dossier->numero_registre ?? '-' }}</dd>

                        <dt class="col-5">Type</dt>
                        <dd class="col-7">{{ ucfirst($decision->type_decision) }}</dd>

                        <dt class="col-5">Date</dt>
                        <dd class="col-7">{{ \Carbon\Carbon::parse($decision->date_decision)->format('d/m/Y') }}</dd>

                        <dt class="col-5">Signée par</dt>
                        <dd class="col-7">{{ $decision->signatures }}</dd>
                    </dl>
                    <div class="mt-3 p-2 bg-light rounded text-muted" style="max-height:100px;overflow-y:auto;font-size:0.8rem;">
                        {{ Str::limit($decision->contenu, 200) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0">Formulaire d'exécution</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('executions.store', $decision) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type de peine <span class="text-danger">*</span></label>
                            @php
                            $peines = [
                                'privative_liberte' => ['label' => 'Privative de liberté', 'desc' => 'Mandat de dépôt ou d\'arrêt — Administration pénitentiaire', 'icon' => 'fa-lock', 'color' => 'danger'],
                                'pecuniaire'        => ['label' => 'Pécuniaire',            'desc' => 'Amende — Trésor public',                                      'icon' => 'fa-coins', 'color' => 'warning'],
                                'complementaire'    => ['label' => 'Complémentaire',        'desc' => 'Interdiction, confiscation, mesure de sûreté',                 'icon' => 'fa-ban',   'color' => 'secondary'],
                            ];
                            @endphp
                            <div class="row g-3 mt-1">
                                @foreach($peines as $value => $peine)
                                <div class="col-md-4">
                                    <label class="d-block" style="cursor:pointer">
                                        <input type="radio" name="type_peine" value="{{ $value }}"
                                            class="btn-check" id="peine_{{ $value }}"
                                            {{ old('type_peine') === $value ? 'checked' : '' }}>
                                        <span class="card border-2 p-3 h-100 d-block text-center border">
                                            <i class="fas {{ $peine['icon'] }} fa-2x text-{{ $peine['color'] }} mb-2 d-block"></i>
                                            <span class="fw-semibold d-block">{{ $peine['label'] }}</span>
                                            <small class="text-muted">{{ $peine['desc'] }}</small>
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('type_peine')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Institution exécutante <span class="text-danger">*</span></label>
                            <select name="id_institution" class="form-select @error('id_institution') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                @foreach($institutions as $inst)
                                <option value="{{ $inst->id_institution }}"
                                    {{ old('id_institution') == $inst->id_institution ? 'selected' : '' }}>
                                    {{ $inst->nom }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_institution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Date d'exécution prévue</label>
                            <input type="date" name="date_execution"
                                class="form-control @error('date_execution') is-invalid @enderror"
                                value="{{ old('date_execution') }}">
                            <div class="form-text">Laisser vide si non encore fixée.</div>
                            @error('date_execution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                            <a href="{{ route('dossiers.show', $decision->dossier) }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="type_peine"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="type_peine"]').forEach(function(r) {
            r.nextElementSibling.className = 'card border-2 p-3 h-100 d-block text-center border';
        });
        const colors = { privative_liberte: 'danger', pecuniaire: 'warning', complementaire: 'secondary' };
        const c = colors[this.value] || 'primary';
        this.nextElementSibling.className = 'card border-2 p-3 h-100 d-block text-center border border-' + c + ' bg-' + c + '-subtle';
    });
});
</script>
@endsection
