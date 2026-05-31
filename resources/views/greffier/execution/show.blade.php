@extends('greffier.layout.app')

@section('content')
@php
$statutColors = ['en_cours' => 'warning', 'executee' => 'success', 'non_executee' => 'danger'];
$statutLabels = ['en_cours' => 'En cours', 'executee' => 'Exécutée', 'non_executee' => 'Non exécutée'];
$peineLabels  = ['privative_liberte' => 'Privative de liberté', 'pecuniaire' => 'Pécuniaire', 'complementaire' => 'Complémentaire'];
$sc = $statutColors[$execution->statut_execution] ?? 'secondary';
$dossier = $execution->decision->dossier ?? null;
@endphp

<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Exécution #{{ $execution->id_execution }}</h4>
            @if($dossier)
            <small class="text-muted">Dossier {{ $dossier->numero_registre }}</small>
            @endif
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-{{ $sc }} {{ $sc === 'warning' ? 'text-dark' : '' }} fs-6">
                {{ $statutLabels[$execution->statut_execution] ?? $execution->statut_execution }}
            </span>
            <a href="{{ route('executions.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Détails exécution --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white"><h5 class="mb-0 text-dark">Détails</h5></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Type de peine</dt>
                        <dd class="col-sm-7">{{ $peineLabels[$execution->type_peine] ?? $execution->type_peine }}</dd>

                        <dt class="col-sm-5">Institution</dt>
                        <dd class="col-sm-7">{{ $execution->institution->nom ?? '—' }}</dd>

                        <dt class="col-sm-5">Date prévue</dt>
                        <dd class="col-sm-7">
                            {{ $execution->date_execution
                                ? \Carbon\Carbon::parse($execution->date_execution)->format('d/m/Y')
                                : '—' }}
                        </dd>

                        <dt class="col-sm-5">Statut</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-{{ $sc }} {{ $sc === 'warning' ? 'text-dark' : '' }}">
                                {{ $statutLabels[$execution->statut_execution] }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            @if($dossier)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white"><h6 class="mb-0">Dossier lié</h6></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-5">Numéro</dt>
                        <dd class="col-7">
                            <a href="{{ route('dossiers.show', $dossier) }}">{{ $dossier->numero_registre }}</a>
                        </dd>
                        <dt class="col-5">Registre</dt>
                        <dd class="col-7">{{ $dossier->registre->nom ?? '-' }}</dd>
                        <dt class="col-5">Statut</dt>
                        <dd class="col-7"><span class="badge bg-info">{{ $dossier->statut }}</span></dd>
                    </dl>
                </div>
            </div>
            @endif

            @if($execution->decision)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h6 class="mb-0">Décision</h6></div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-5">Type</dt>
                        <dd class="col-7">{{ ucfirst($execution->decision->type_decision) }}</dd>
                        <dt class="col-5">Date</dt>
                        <dd class="col-7">{{ \Carbon\Carbon::parse($execution->decision->date_decision)->format('d/m/Y') }}</dd>
                        <dt class="col-5">Signataire</dt>
                        <dd class="col-7">{{ $execution->decision->signatures }}</dd>
                    </dl>
                    <div class="mt-2 p-2 bg-light rounded text-muted" style="max-height:100px;overflow-y:auto;">
                        {{ Str::limit($execution->decision->contenu, 200) }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Mise à jour statut --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><h5 class="mb-0 text-dark">Mettre à jour le statut</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('executions.statut', $execution) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nouveau statut <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach(['en_cours' => ['En cours', 'warning', 'fa-hourglass-half'], 'executee' => ['Exécutée', 'success', 'fa-check-circle'], 'non_executee' => ['Non exécutée', 'danger', 'fa-times-circle']] as $val => [$label, $color, $icon])
                                <div class="col-md-4">
                                    <label class="d-block" style="cursor:pointer">
                                        <input type="radio" name="statut_execution" value="{{ $val }}"
                                            class="btn-check" id="statut_{{ $val }}"
                                            {{ $execution->statut_execution === $val ? 'checked' : '' }}>
                                        <span class="card border-2 p-3 text-center d-block
                                            {{ $execution->statut_execution === $val ? 'border-'.$color.' bg-'.$color.'-subtle' : 'border' }}">
                                            <i class="fas {{ $icon }} fa-2x text-{{ $color }} mb-1 d-block"></i>
                                            <span class="fw-semibold">{{ $label }}</span>
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('statut_execution')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Date d'exécution effective</label>
                            <input type="date" name="date_execution"
                                class="form-control @error('date_execution') is-invalid @enderror"
                                value="{{ old('date_execution', $execution->date_execution) }}">
                            @error('date_execution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer le statut
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="statut_execution"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const colors = { en_cours: 'warning', executee: 'success', non_executee: 'danger' };
        document.querySelectorAll('input[name="statut_execution"]').forEach(function(r) {
            r.nextElementSibling.className = 'card border-2 p-3 text-center d-block border';
        });
        const c = colors[this.value] || 'primary';
        this.nextElementSibling.className = 'card border-2 p-3 text-center d-block border border-' + c + ' bg-' + c + '-subtle';
    });
});
</script>
@endsection
