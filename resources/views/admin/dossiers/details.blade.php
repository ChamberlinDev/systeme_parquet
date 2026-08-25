@extends('admin.layout.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid py-4 text-dark">

    {{-- ===== EN-TÊTE DOSSIER ===== --}}
    <div class="card shadow-sm border-0 mb-4 bg-primary">
        <div class="card-body p-4 text-white">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="text-white-50 small text-uppercase mb-1" style="letter-spacing:1px">Dossier Judiciaire</div>
                    <h2 class="fw-bold mb-1" style="letter-spacing: 2px;">{{ $dossier->numero_rp }}</h2>
                    <div class="text-white-50">{{ $dossier->numero_registre }}</div>
                </div>
                <div class="text-end">
                    @php
                    $statutColors = [
                    'En cours' => 'warning',
                    'Classé' => 'success',
                    'Archivé' => 'secondary',
                    'Suspendu' => 'danger',
                    ];
                    $color = $statutColors[$dossier->statut] ?? 'primary';
                    @endphp
                    <span class="badge bg-{{ $color }} mb-2 px-3 py-2">
                        <i class="fas fa-circle me-1" style="font-size:0.6rem"></i>
                        {{ $dossier->statut }}
                    </span>
                    <div class="text-white-50 small">
                        Créé le {{ $dossier->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===== COLONNE GAUCHE ===== --}}
        <div class="col-lg-7">

            {{-- Informations générales --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold border-bottom">
                    <i class="fas fa-folder-open text-primary me-2"></i> Informations générales
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small text-uppercase fw-bold">Type d'affaire</div>
                            <div class="mt-1">
                                <span class="badge bg-primary">{{ $dossier->registre->nom ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small text-uppercase fw-bold">Parquet</div>
                            <div class="mt-1">
                                <i class="fas fa-building text-muted me-1"></i>
                                {{ $dossier->parquet->nom ?? '—' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small text-uppercase fw-bold">Date de la demande</div>
                            <div class="mt-1">
                                <i class="fas fa-calendar text-dark me-1"></i>
                                {{ \Carbon\Carbon::parse($dossier->date_demande)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small text-uppercase fw-bold">Greffier responsable</div>
                            <div class="mt-1">
                                <i class="fas fa-user text-dark me-1"></i>
                                {{ $dossier->greffier->name ?? '—' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small text-uppercase fw-bold">Dernière modification</div>
                            <div class="mt-1 text-muted small">
                                {{ $dossier->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small text-uppercase fw-bold">Statut</div>
                            <div class="mt-1">
                                <span class="badge bg-{{ $color }}">{{ $dossier->statut }}</span>
                            </div>
                        </div>
                    </div>

                    @if($dossier->nature_infraction)
                    <hr>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-2">Nature de l'infraction</div>
                        <div class="p-3 bg-light rounded">{{ $dossier->nature_infraction }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Parties --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold border-bottom">
                    <i class="fas fa-users text-primary me-2"></i> Parties ({{ $dossier->parties->count() }})
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Contact</th>
                                <th>Rôle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dossier->parties as $partie)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $partie->nom }}</td>
                                <td>{{ $partie->prenom ?? '—' }}</td>
                                <td>{{ $partie->contact ?? '—' }}</td>
                                <td>
                                    @php
                                    $roleColors = [
                                    'Plaignant' => 'primary',
                                    'Défendeur' => 'danger',
                                    'Témoin' => 'warning',
                                    ];
                                    $rc = $roleColors[$partie->role] ?? 'white';
                                    @endphp
                                    <span class="badge bg-{{ $rc }}">{{ $partie->role }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Aucune partie enregistrée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ===== COLONNE DROITE ===== --}}
        <div class="col-lg-5">

            {{-- Références --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold border-bottom">
                    <i class="fas fa-hashtag text-primary me-2"></i> Références
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small text-uppercase fw-bold">Numéro RP</div>
                        <div class="fw-bold text-primary fs-5 mt-1">{{ $dossier->numero_rp }}</div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small text-uppercase fw-bold">Numéro Registre</div>
                        <div class="fw-bold mt-1">{{ $dossier->numero_registre }}</div>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Type d'affaire</div>
                        <div class="mt-1">{{ $dossier->registre->nom ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Pièces jointes --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold border-bottom">
                    <i class="fas fa-paperclip text-primary me-2"></i> Pièces jointes ({{ $dossier->files->count() }})
                </div>
                <div class="card-body">
                    @forelse($dossier->files as $index => $file)
                    <a href="{{ asset('storage/' . $file->file_path) }}"
                        target="_blank"
                        class="d-flex align-items-center gap-2 text-decoration-none text-danger border border-danger-subtle rounded p-2 mb-2">
                        <i class="fas fa-file-pdf fa-lg"></i>
                        <span class=" text-dark small">Document {{ $index + 1 }}</span>
                        <i class="fas fa-external-link-alt fa-sm"></i>
                    </a>
                    @empty
                    <p class="text-muted mb-0 text-center py-2">Aucune pièce jointe</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- BOUTONS détails --}}
    <div class="d-flex gap-2 mt-4 flex-wrap">

        {{-- Retour --}}
        <a href="{{ route('accueil.admin') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
    @endsection