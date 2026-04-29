@extends('greffier.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- HEADER --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">
                                <i class="fas fa-pen text-warning me-2"></i>Modifier le Dossier
                            </h4>
                            <p class="text-muted mb-0 small">
                                <span class="fw-semibold text-dark">{{ $dossier->numero_registre }}</span>
                                · {{ $dossier->numero_rp }}
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @php
                                $colors = [
                                    'En cours' => 'warning',
                                    'Clôturé'  => 'success',
                                    'Archivé'  => 'secondary',
                                    'Suspendu' => 'danger',
                                    'Orienté'  => 'info',
                                ];
                                $color = $colors[$dossier->statut] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} fs-6 px-3 py-2">{{ $dossier->statut }}</span>
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-landmark me-1"></i>{{ auth()->user()->parquet->nom }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('dossiers.update', $dossier->id_dossier) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ÉTAPE 1 : INFORMATIONS GÉNÉRALES --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <span class="badge bg-primary rounded-circle me-2">1</span>
                            Informations générales
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Numéro RP
                                    <span class="text-muted fw-normal">(non modifiable)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-hashtag text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ $dossier->numero_rp }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Type d'affaire
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('id_registre') is-invalid @enderror"
                                        name="id_registre" id="typeAffaire" required>
                                    <option value="">— Sélectionner —</option>
                                    @foreach($registres as $registre)
                                    <option value="{{ $registre->id_registre }}"
                                        data-prefix="{{ $registre->code }}"
                                        {{ old('id_registre', $dossier->id_registre) == $registre->id_registre ? 'selected' : '' }}>
                                        {{ $registre->nom }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_registre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Numéro de dossier
                                    <span class="text-muted fw-normal">(non modifiable)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-file-alt text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                           id="numeroRegistre"
                                           value="{{ $dossier->numero_registre }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Date de la demande
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar text-muted"></i>
                                    </span>
                                    <input type="date"
                                           class="form-control @error('date_demande') is-invalid @enderror"
                                           name="date_demande"
                                           value="{{ old('date_demande', $dossier->date_demande) }}"
                                           required>
                                </div>
                                @error('date_demande')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Greffier responsable</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ $dossier->greffier->name ?? auth()->user()->name }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Nature de l'infraction</label>
                                <textarea class="form-control" name="nature_infraction" rows="3"
                                    placeholder="Décrivez les faits...">{{ old('nature_infraction', $dossier->nature_infraction) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ÉTAPE 2 : PARTIES --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-semibold">
                                <span class="badge bg-primary rounded-circle me-2">2</span>
                                Parties impliquées
                            </h6>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addPartie">
                                <i class="fas fa-plus me-1"></i> Ajouter une partie
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-2 mb-2 d-none d-md-flex">
                            <div class="col-md-3"><small class="text-muted fw-semibold">NOM *</small></div>
                            <div class="col-md-3"><small class="text-muted fw-semibold">PRÉNOM</small></div>
                            <div class="col-md-3"><small class="text-muted fw-semibold">CONTACT</small></div>
                            <div class="col-md-3"><small class="text-muted fw-semibold">RÔLE</small></div>
                        </div>
                        <div id="partiesContainer">
                            @foreach($dossier->parties as $index => $partie)
                            <div class="row g-2 mb-2 partie-row align-items-center">
                                <input type="hidden"
                                       name="parties[{{ $index }}][id]"
                                       value="{{ $partie->id }}">
                                <div class="col-md-3">
                                    <input type="text"
                                           name="parties[{{ $index }}][nom]"
                                           class="form-control"
                                           placeholder="Nom *"
                                           value="{{ old("parties.$index.nom", $partie->nom) }}"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text"
                                           name="parties[{{ $index }}][prenom]"
                                           class="form-control"
                                           placeholder="Prénom"
                                           value="{{ old("parties.$index.prenom", $partie->prenom) }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text"
                                           name="parties[{{ $index }}][contact]"
                                           class="form-control"
                                           placeholder="Contact"
                                           value="{{ old("parties.$index.contact", $partie->contact) }}">
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <select name="parties[{{ $index }}][role]" class="form-select">
                                        @foreach(['Plaignant', 'Défendeur', 'Témoin'] as $role)
                                        <option value="{{ $role }}"
                                            {{ old("parties.$index.role", $partie->role) === $role ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            onclick="this.closest('.partie-row').remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ÉTAPE 3 : PIÈCES JOINTES --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <span class="badge bg-primary rounded-circle me-2">3</span>
                            Pièces jointes
                            <span class="text-muted fw-normal small ms-1">(PDF uniquement, max 5 Mo)</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Fichiers existants --}}
                        @if($dossier->files->count())
                        <div class="mb-3">
                            <small class="text-muted fw-semibold d-block mb-2">FICHIERS EXISTANTS</small>
                            @foreach($dossier->files as $file)
                            <div class="d-flex align-items-center justify-content-between
                                        border rounded px-3 py-2 mb-2 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-file-pdf text-danger"></i>
                                    <small>{{ basename($file->file_path) }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ asset('storage/' . $file->file_path) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox"
                                               name="delete_files[]"
                                               value="{{ $file->id }}"
                                               id="delFile{{ $file->id }}">
                                        <label class="form-check-label text-danger small"
                                               for="delFile{{ $file->id }}">
                                            Supprimer
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Nouveaux fichiers --}}
                        <small class="text-muted fw-semibold d-block mb-2">AJOUTER DE NOUVEAUX FICHIERS</small>
                        <input type="file"
                               class="form-control @error('pdf_files') is-invalid @enderror"
                               name="pdf_files[]" accept="application/pdf" multiple>
                        @error('pdf_files')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Vous pouvez sélectionner plusieurs fichiers PDF.
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex gap-3 justify-content-end mb-5">
                    <a href="{{ route('dossiers.index.greffier') }}"
                       class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning px-5 text-white">
                        <i class="fas fa-save me-2"></i> Enregistrer les modifications
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    let partieIndex = {{ $dossier->parties->count() }};

    document.getElementById('addPartie').addEventListener('click', function () {
        const container = document.getElementById('partiesContainer');
        const row       = document.createElement('div');
        row.classList.add('row', 'g-2', 'mb-2', 'partie-row', 'align-items-center');
        row.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="parties[${partieIndex}][nom]"
                    class="form-control" placeholder="Nom *" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="parties[${partieIndex}][prenom]"
                    class="form-control" placeholder="Prénom">
            </div>
            <div class="col-md-3">
                <input type="text" name="parties[${partieIndex}][contact]"
                    class="form-control" placeholder="Contact">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <select name="parties[${partieIndex}][role]" class="form-select">
                    <option value="Plaignant">Plaignant</option>
                    <option value="Défendeur">Défendeur</option>
                    <option value="Témoin">Témoin</option>
                </select>
                <button type="button" class="btn btn-outline-danger"
                        onclick="this.closest('.partie-row').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        partieIndex++;
    });
</script>
@endsection