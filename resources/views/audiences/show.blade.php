@php
    $user = auth()->user();
    $layout = 'admin.layout.app';
    $backRoute = 'audiences.index.greffier';

    if ($user?->hasRole('greffier')) {
        $layout = 'greffier.layout.app';
        $backRoute = 'audiences.index.greffier';
    } elseif ($user?->hasRole('procureur')) {
        $layout = 'procureur.layout.app';
        $backRoute = 'audiences.index.procureur';
    } elseif ($user?->hasRole('juge')) {
        $layout = 'juge.layout.app';
        $backRoute = 'audiences.index.juge';
    }

    $typeColors = [
        'correctionnelle' => 'warning',
        'criminelle'      => 'danger',
        'sociale'         => 'info',
        'refere'          => 'secondary',
    ];
    $tc = $typeColors[$audience->type_audience] ?? 'secondary';
@endphp

@extends($layout)

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                Audience du {{ \Carbon\Carbon::parse($audience->date_audience)->format('d/m/Y') }}
            </h4>
            <small class="text-muted">Salle : {{ $audience->salle }}</small>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-{{ $tc }} {{ $tc === 'warning' ? 'text-dark' : '' }} fs-6">
                {{ ucfirst($audience->type_audience) }}
            </span>
            <a href="{{ route($backRoute) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Colonne gauche : dossiers du rôle --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">Rôle — Dossiers inscrits</h5>
                    <span class="badge bg-light border text-dark">{{ $audience->dossiers->count() }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($audience->dossiers as $dossier)
                    @php
                        $decision = $dossier->decisions->first();
                    @endphp
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <a href="{{ route('dossiers.show', $dossier) }}" class="fw-semibold text-primary">
                                    {{ $dossier->numero_registre }}
                                </a>
                                <div class="small text-muted">{{ $dossier->registre->nom ?? '-' }}</div>
                                @if($dossier->parties->isNotEmpty())
                                <div class="small text-muted">
                                    {{ $dossier->parties->first()->nom }} {{ $dossier->parties->first()->prenom }}
                                    @if($dossier->parties->count() > 1)
                                        + {{ $dossier->parties->count() - 1 }} autre(s)
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1">
                                @if($decision)
                                    <span class="badge bg-success">Décision rendue</span>
                                @elseif($user?->hasRole('juge'))
                                    <a href="{{ route('decisions.create', [$audience, $dossier]) }}"
                                       class="btn btn-sm btn-outline-dark">
                                        <i class="fas fa-gavel"></i> Décider
                                    </a>
                                @else
                                    <span class="badge bg-light border text-muted">En attente</span>
                                @endif
                            </div>
                        </div>

                        @if($decision)
                        <div class="mt-2 p-2 bg-light rounded small">
                            <span class="fw-semibold text-dark">{{ ucfirst($decision->type_decision) }}</span>
                            du {{ \Carbon\Carbon::parse($decision->date_decision)->format('d/m/Y') }}<br>
                            <span class="text-muted">{{ Str::limit($decision->contenu, 120) }}</span>
                        </div>
                        @endif
                    </li>
                    @empty
                    <li class="list-group-item text-muted text-center py-3">Aucun dossier inscrit</li>
                    @endforelse
                </ul>
            </div>

            @if($audience->role)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 text-dark">Notes du rôle</h6>
                </div>
                <div class="card-body small text-muted">{{ $audience->role }}</div>
            </div>
            @endif
        </div>

        {{-- Colonne droite : PV --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">Procès-verbal d'audience</h5>
                    @if($audience->pv)
                        <span class="badge bg-success"><i class="fas fa-check"></i> Saisi</span>
                    @else
                        <span class="badge bg-light border text-muted">Non saisi</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($audience->pv && !$user?->hasRole('greffier'))
                        <div class="p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $audience->pv }}</div>
                    @elseif($user?->hasRole('greffier'))
                        <form method="POST" action="{{ route('audiences.pv', $audience) }}">
                            @csrf
                            <div class="mb-3">
                                <textarea name="pv" rows="12"
                                    class="form-control @error('pv') is-invalid @enderror"
                                    placeholder="Saisir le procès-verbal des débats...">{{ old('pv', $audience->pv) }}</textarea>
                                @error('pv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i>
                                {{ $audience->pv ? 'Mettre à jour le PV' : 'Enregistrer le PV' }}
                            </button>
                        </form>
                    @else
                        <div class="text-muted text-center py-5">
                            <i class="fas fa-file-alt fa-3x mb-3 opacity-25 d-block"></i>
                            Le PV n'a pas encore été saisi par le greffier.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
