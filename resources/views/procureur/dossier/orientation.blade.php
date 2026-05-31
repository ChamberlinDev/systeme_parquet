@extends('procureur.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Decision d'orientation</h4>
            <small class="text-muted">{{ $dossier->numero_rp }} &mdash; {{ $dossier->numero_registre }}</small>
        </div>
        <a href="{{ route('dossiers.show', $dossier) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour au dossier
        </a>
    </div>

    <div class="row">
        {{-- Recap dossier --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-dark">Recap du dossier</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5">Registre</dt>
                        <dd class="col-7">{{ $dossier->registre->nom ?? '-' }}</dd>

                        <dt class="col-5">Date demande</dt>
                        <dd class="col-7">{{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}</dd>

                        <dt class="col-5">Parquet</dt>
                        <dd class="col-7">{{ $dossier->parquet_competent ?: '-' }}</dd>

                        <dt class="col-5">Infraction</dt>
                        <dd class="col-7">{{ $dossier->nature_infraction ?: '-' }}</dd>

                        <dt class="col-5">Statut actuel</dt>
                        <dd class="col-7">
                            <span class="badge bg-warning text-dark">{{ $dossier->statut }}</span>
                        </dd>
                    </dl>
                </div>
            </div>

            @if($dossier->parties->isNotEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-dark">Parties ({{ $dossier->parties->count() }})</h6>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($dossier->parties as $partie)
                    <li class="list-group-item small">
                        <span class="fw-semibold">{{ $partie->nom }} {{ $partie->prenom }}</span>
                        <span class="badge bg-light border text-dark ms-1">{{ $partie->role }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        {{-- Formulaire orientation --}}
        <div class="col-lg-8 mb-4">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($dossier->decision_orientation)
            <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
                <i class="fas fa-info-circle"></i>
                <div>
                    Une orientation a déjà été enregistrée pour ce dossier.
                    Soumettre ce formulaire <strong>remplacera</strong> la décision précédente.
                </div>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-dark">Formulaire de decision d'orientation</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dossiers.orientation.store', $dossier) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Decision d'orientation <span class="text-danger">*</span></label>

                            @php
                            $options = [
                                'classement_sans_suite'    => ['label' => 'Classement sans suite',    'desc' => 'Charges insuffisantes ou action publique non opportune.',        'color' => 'danger'],
                                'citation_directe'         => ['label' => 'Citation directe',         'desc' => 'Assignation directe devant le tribunal (affaires simples).',      'color' => 'primary'],
                                'comparution_immediate'    => ['label' => 'Comparution immédiate',    'desc' => 'Flagrants délits — jugement rapide.',                             'color' => 'warning'],
                                'requisitoire_introductif' => ['label' => 'Réquisitoire introductif', 'desc' => 'Saisine du juge d\'instruction (affaires graves ou complexes).', 'color' => 'dark'],
                                'mediation_penale'         => ['label' => 'Médiation pénale',         'desc' => 'Alternative aux poursuites — résolution amiable du conflit.',     'color' => 'success'],
                                'renvoi_administratif'     => ['label' => 'Renvoi administratif',     'desc' => 'Transmission à l\'autorité administrative compétente.',           'color' => 'secondary'],
                            ];
                            @endphp

                            <div class="row g-3 mt-1">
                                @foreach($options as $value => $opt)
                                <div class="col-md-6">
                                    <label class="d-block cursor-pointer">
                                        <input type="radio" name="decision_orientation" value="{{ $value }}"
                                            class="btn-check"
                                            id="opt_{{ $value }}"
                                            {{ old('decision_orientation', $dossier->decision_orientation) === $value ? 'checked' : '' }}>
                                        <span class="card border-2 p-3 h-100 d-block
                                            {{ old('decision_orientation', $dossier->decision_orientation) === $value ? 'border-'.$opt['color'].' bg-'.$opt['color'].'-subtle' : 'border' }}"
                                            style="cursor:pointer">
                                            <span class="fw-semibold text-{{ $opt['color'] }}">{{ $opt['label'] }}</span>
                                            <br>
                                            <small class="text-muted">{{ $opt['desc'] }}</small>
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('decision_orientation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="date_orientation" class="form-label fw-semibold">
                                Date de la decision <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="date_orientation" id="date_orientation"
                                class="form-control @error('date_orientation') is-invalid @enderror"
                                value="{{ old('date_orientation', $dossier->date_orientation ?? date('Y-m-d')) }}">
                            @error('date_orientation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="motif_orientation" class="form-label fw-semibold">
                                Motif et observations <span class="text-danger">*</span>
                            </label>
                            <textarea name="motif_orientation" id="motif_orientation" rows="5"
                                class="form-control @error('motif_orientation') is-invalid @enderror"
                                placeholder="Exposer les raisons juridiques justifiant cette decision...">{{ old('motif_orientation', $dossier->motif_orientation) }}</textarea>
                            @error('motif_orientation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Enregistrer la decision
                            </button>
                            <a href="{{ route('dossiers.show', $dossier) }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="decision_orientation"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="decision_orientation"]').forEach(function(r) {
            const card = r.nextElementSibling;
            card.className = 'card border-2 p-3 h-100 d-block border';
            card.style.cursor = 'pointer';
        });
        const selected = document.querySelector('input[name="decision_orientation"]:checked');
        if (selected) {
            const card = selected.nextElementSibling;
            const colors = {
                'classement_sans_suite':    'danger',
                'citation_directe':         'primary',
                'comparution_immediate':    'warning',
                'requisitoire_introductif': 'dark',
                'mediation_penale':         'success',
                'renvoi_administratif':     'secondary',
            };
            const c = colors[selected.value] || 'primary';
            card.className = 'card border-2 p-3 h-100 d-block border-' + c + ' bg-' + c + '-subtle';
            card.style.cursor = 'pointer';
        }
    });
});
</script>
@endsection
