@php
    $user = auth()->user();
    $layout = 'admin.layout.app';
    $backRoute = 'dossiers.index';

    if ($user?->hasRole('greffier')) {
        $layout = 'greffier.layout.app';
        $backRoute = 'dossiers.index.greffier';
    } elseif ($user?->hasRole('procureur')) {
        $layout = 'procureur.layout.app';
        $backRoute = 'dossiers.index.procureur';
    } elseif ($user?->hasRole('juge')) {
        $layout = 'juge.layout.app';
        $backRoute = 'dossiers.index.juge';
    }

    $firstFile = $dossier->files->first();
    $dossier->loadMissing('audiences', 'decisions');

    $orientationLabels = [
        'classement_sans_suite'    => 'Classement sans suite',
        'citation_directe'         => 'Citation directe',
        'comparution_immediate'    => 'Comparution immédiate',
        'requisitoire_introductif' => 'Réquisitoire introductif',
    ];
    $orientationColors = [
        'classement_sans_suite'    => 'danger',
        'citation_directe'         => 'primary',
        'comparution_immediate'    => 'warning',
        'requisitoire_introductif' => 'dark',
    ];
    $statutColors = [
        'En cours'       => 'warning',
        'Orienté'        => 'info',
        'En instruction' => 'primary',
        'Classé'         => 'secondary',
        'Cloture'        => 'success',
        'Archive'        => 'secondary',
        'Suspendu'       => 'danger',
    ];
@endphp

@extends($layout)

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Dossier {{ $dossier->numero_registre }}</h4>
            <small class="text-muted">{{ $dossier->numero_rp }}</small>
        </div>
        <div class="d-flex gap-2">
            @if($user?->hasRole('procureur'))
                <a href="{{ route('dossiers.orientation.form', $dossier) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-gavel"></i>
                    {{ $dossier->decision_orientation ? 'Modifier l\'orientation' : 'Décider orientation' }}
                </a>
            @endif
            <a href="{{ route($backRoute) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-dark">Informations generales</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Registre</dt>
                        <dd class="col-sm-7">{{ $dossier->registre->nom ?? '-' }}</dd>

                        <dt class="col-sm-5">Statut</dt>
                        <dd class="col-sm-7">
                            @php $sc = $statutColors[$dossier->statut] ?? 'secondary'; @endphp
                            <span class="badge bg-{{ $sc }} {{ $sc === 'warning' ? 'text-dark' : '' }}">{{ $dossier->statut }}</span>
                        </dd>

                        <dt class="col-sm-5">Date demande</dt>
                        <dd class="col-sm-7">{{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}</dd>

                        <dt class="col-sm-5">Parquet</dt>
                        <dd class="col-sm-7">{{ $dossier->parquet_competent ?: '-' }}</dd>

                        <dt class="col-sm-5">Infraction</dt>
                        <dd class="col-sm-7">{{ $dossier->nature_infraction ?: '-' }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Bloc orientation --}}
            @if($dossier->decision_orientation)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">Decision d'orientation</h5>
                    @php $oc = $orientationColors[$dossier->decision_orientation] ?? 'secondary'; @endphp
                    <span class="badge bg-{{ $oc }} {{ $oc === 'warning' ? 'text-dark' : '' }}">
                        {{ $orientationLabels[$dossier->decision_orientation] ?? $dossier->decision_orientation }}
                    </span>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-sm-5">Date</dt>
                        <dd class="col-sm-7">{{ \Carbon\Carbon::parse($dossier->date_orientation)->format('d/m/Y') }}</dd>

                        <dt class="col-sm-5">Motif</dt>
                        <dd class="col-sm-7">{{ $dossier->motif_orientation }}</dd>
                    </dl>
                </div>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-dark">Parties</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Contact</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dossier->parties as $partie)
                                <tr>
                                    <td>{{ trim($partie->nom . ' ' . $partie->prenom) }}</td>
                                    <td>{{ $partie->contact ?: '-' }}</td>
                                    <td>{{ $partie->role }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center py-3">Aucune partie enregistree</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            {{-- Audiences liées --}}
            @if($dossier->audiences->isNotEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">Audiences</h5>
                    <span class="badge bg-light border text-dark">{{ $dossier->audiences->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($dossier->audiences as $audience)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($audience->date_audience)->format('d/m/Y') }}</span>
                            <span class="text-muted small ms-2">{{ $audience->salle }}</span>
                            <span class="badge bg-light border text-dark ms-1">{{ ucfirst($audience->type_audience) }}</span>
                        </div>
                        <a href="{{ route('audiences.show', $audience) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Décisions rendues --}}
            @if($dossier->decisions->isNotEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-dark">Décisions judiciaires</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($dossier->decisions as $dec)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-semibold">{{ ucfirst($dec->type_decision) }}</span>
                            <span class="text-muted small">{{ \Carbon\Carbon::parse($dec->date_decision)->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-muted small mt-1">{{ Str::limit($dec->contenu, 150) }}</div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Historique --}}
            @if($dossier->historique->isNotEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-dark">Historique des actions</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($dossier->historique as $log)
                    <li class="list-group-item small">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">{{ $log->action }}</span>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($log->detail)
                            <div class="text-muted mt-1">{{ $log->detail }}</div>
                        @endif
                        @if($log->user)
                            <div class="text-muted" style="font-size:0.75rem">
                                Par : {{ $log->user->name }}
                            </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">Pieces jointes</h5>
                    <span class="badge bg-light border text-dark">{{ $dossier->files->count() }} fichier(s)</span>
                </div>
                <div class="card-body">
                    @if($dossier->files->isEmpty())
                        <div class="text-muted text-center py-5">
                            <i class="fas fa-file-pdf fa-3x mb-3 opacity-25 d-block"></i>
                            Aucun fichier PDF pour ce dossier.
                        </div>
                    @else
                        <div class="list-group mb-3">
                            @foreach($dossier->files as $file)
                                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                   href="{{ route('dossiers.files.show', [$dossier, $file]) }}"
                                   target="_blank">
                                    <span>
                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                        {{ basename($file->file_path) }}
                                    </span>
                                    <span class="btn btn-sm btn-outline-primary">Voir</span>
                                </a>
                            @endforeach
                        </div>

                        @if($firstFile)
                            <div class="border rounded overflow-hidden">
                                <iframe
                                    src="{{ route('dossiers.files.show', [$dossier, $firstFile]) }}"
                                    style="width: 100%; height: 620px; border: 0;"
                                    title="Apercu PDF du dossier {{ $dossier->numero_registre }}">
                                </iframe>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
