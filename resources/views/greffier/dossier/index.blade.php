@extends('greffier.layout.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<div class="container-fluid py-4">

    {{-- ===================== STATISTIQUES ===================== --}}
    <div class="row g-4 mb-4">

        @php
        $total = $dossiers->count();
        $enCours = $dossiers->where('statut', 'En cours')->count();
        $traites = $dossiers->where('statut', 'Clôturé')->count();
        $archives = $dossiers->where('statut', 'Archivé')->count();
        @endphp

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Total Dossiers</div>
                        <div class="fs-4 fw-bold text-dark">{{ $total }}</div>
                    </div>
                    <i class="fas fa-folder-open fa-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- En cours --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">En cours</div>
                        <div class="fs-4 fw-bold text-warning">{{ $enCours }}</div>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x text-warning opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Traités --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Clôturés</div>
                        <div class="fs-4 fw-bold text-success">{{ $traites }}</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Archivés --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Archivés</div>
                        <div class="fs-4 fw-bold text-secondary">{{ $archives }}</div>
                    </div>
                    <i class="fas fa-archive fa-2x text-secondary opacity-75"></i>
                </div>
            </div>
        </div>

    </div>



    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark mb-0">Liste des Dossiers</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('dossiers.create.form') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nouveau
            </a>
            <a href="#" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th style="width:35%">Dossier</th>
                        <th>Registre</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Greffier</th>
                        <th class="text-center">Actions</th>

                    </tr>
                </thead>

                <tbody>
                    @forelse($dossiers as $dossier)
                    <tr>

                        {{-- DOSSIER --}}
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-folder-open fa-2x text-warning"></i>
                                <div>
                                    <a href="{{ route('dossiers.show', $dossier->id_dossier) }}" class="text-primary">
                                        <div class="fw-semibold"> {{ $dossier->numero_registre }}</div>
                                    </a>
                                    <small class="text-muted">

                                        · {{ $dossier->parties->count() }} partie(s)
                                        @if($dossier->files->count())
                                        · {{ $dossier->files->count() }} fichier(s)
                                        @endif
                                    </small>

                                </div>
                            </div>
                        </td>

                        {{-- REGISTRE --}}
                        <td>

                            <span class="badge bg-light border text-dark">
                                {{ $dossier->registre->nom ?? '—' }}
                            </span>
                        </td>

                        {{-- STATUT --}}
                        <td>
                            @php
                            $colors = [
                            'En cours' => 'warning',
                            'Clôturé' => 'success',
                            'Archivé' => 'secondary',
                            'Suspendu' => 'danger',
                            ];
                            $color = $colors[$dossier->statut] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $dossier->statut }}</span>
                        </td>

                        {{-- DATE --}}
                        <td>
                            <div>{{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}</div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($dossier->date_demande)->diffForHumans() }}
                            </small>
                        </td>
                        <td>
                            {{ $dossier->greffier->name ?? '—' }}
                        </td>
                        {{-- ACTIONS --}}

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('dossiers.show', $dossier->id_dossier) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="#" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Supprimer"
                                        onclick="return confirm('Supprimer ce dossier ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>


                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25 d-block"></i>
                            Aucun dossier trouvé
                            <div class="mt-3">
                                <a href="{{ route('dossiers.create.form') }}" class="btn btn-primary btn-sm">
                                    Créer un dossier
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if(method_exists($dossiers, 'links'))
        <div class="card-footer bg-white">
            {{ $dossiers->links() }}
        </div>
        @endif
    </div>

</div>

@endsection