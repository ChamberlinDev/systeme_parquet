@extends('greffier.layout.app')

@section('content')

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
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

        {{-- HEADER --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body py-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-1">
                            <i class="fas fa-folder-plus text-primary me-2"></i>Créer un Dossier
                        </h4>
                        <p class="text-muted mb-0 small">Système de Gestion des Dossiers Judiciaires</p>
                    </div>
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-landmark me-1"></i>{{ auth()->user()->parquet->nom }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('dossiers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                                <span class="text-muted fw-normal">(généré auto.)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-hashtag text-muted"></i></span>
                                <input type="text" class="form-control bg-light" name="numero_rp"
                                    value="{{ $numbers['numero_rp'] }}" readonly>
                            </div>
                        </div>

                           <div class="col-md-6">
                            <label class="form-label fw-semibold small">Type d'affaire <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_registre') is-invalid @enderror"
                                name="id_registre" id="typeAffaire" required>
                                <option value="">— Sélectionner —</option>
                                @foreach($registres as $registre)
                                <option value="{{ $registre->id_registre }}"
                                    data-prefix="{{ $registre->code }}"
                                    {{ old('id_registre') == $registre->id_registre ? 'selected' : '' }}>
                                    {{ $registre->nom }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_registre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Numéro de dossier
                                <span class="text-muted fw-normal">(généré auto.)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-file-alt text-muted"></i></span>
                                <input type="text" class="form-control bg-light" name="numero_registre"
                                    id="numeroRegistre" value="{{ $numbers['numero_registre'] }}" readonly>
                            </div>
                        </div>

                     

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Date de la demande <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar text-muted"></i></span>
                                <input type="date" class="form-control @error('date_demande') is-invalid @enderror"
                                    name="date_demande" value="{{ old('date_demande', date('Y-m-d')) }}" required>
                            </div>
                            @error('date_demande') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Greffier responsable</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" class="form-control bg-light"
                                    value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Nature de l'infraction</label>
                            <textarea class="form-control" name="nature_infraction" rows="3"
                                placeholder="Décrivez les faits...">{{ old('nature_infraction') }}</textarea>
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
                        <div class="row g-2 mb-2 partie-row align-items-center">
                            <div class="col-md-3">
                                <input type="text" name="parties[0][nom]" class="form-control"
                                    placeholder="Nom *" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="parties[0][prenom]" class="form-control" placeholder="Prénom">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="parties[0][contact]" class="form-control" placeholder="Contact">
                            </div>
                            <div class="col-md-3">
                                <select name="parties[0][role]" class="form-select">
                                    <option value="Plaignant">Plaignant</option>
                                    <option value="Défendeur">Défendeur</option>
                                    <option value="Témoin">Témoin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ÉTAPE 3 : ORIENTATION / TRANSFERT --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0 fw-semibold">
                            <span class="badge bg-primary rounded-circle me-2">3</span>
                            Orientation & Transfert
                            <span class="text-muted fw-normal small ms-1">(optionnel)</span>
                        </h6>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="toggleTransfert" role="switch">
                            <label class="form-check-label small text-muted" for="toggleTransfert">
                                Transférer à une autorité
                            </label>
                        </div>
                    </div>
                </div>

               
                {{-- Si pas de transfert, statut par défaut --}}
                <div class="card-body py-3 px-4 border-top" id="statutDefaut">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle text-muted"></i>
                        <small class="text-muted">Statut par défaut :
                            <span class="badge bg-warning text-dark">En cours</span>
                        </small>
                    </div>
                </div>
            </div>

            {{-- ÉTAPE 4 : PIÈCES JOINTES --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <span class="badge bg-primary rounded-circle me-2">4</span>
                        Pièces jointes
                        <span class="text-muted fw-normal small ms-1">(PDF uniquement, max 5 Mo)</span>
                    </h6>
                </div>
                <div class="card-body p-4">
                    <input type="file"
                        class="form-control @error('pdf_files') is-invalid @enderror"
                        name="pdf_files[]" accept="application/pdf" multiple>
                    @error('pdf_files') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Vous pouvez sélectionner plusieurs fichiers PDF.
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex gap-3 justify-content-end mb-5">
                <a href="{{ route('dossiers.index.greffier') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-times me-1"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i> Enregistrer le dossier
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    let partieIndex = 1;

    // Toggle section transfert
    document.getElementById('toggleTransfert').addEventListener('change', function () {
        const section = document.getElementById('transfertSection');
        const defaut  = document.getElementById('statutDefaut');
        const select  = document.getElementById('procureurSelect');

        if (this.checked) {
            section.style.display = 'block';
            defaut.style.display  = 'none';
            select.setAttribute('required', 'required');
        } else {
            section.style.display = 'none';
            defaut.style.display  = 'block';
            select.removeAttribute('required');
        }
    });

    // Numéro registre dynamique
    document.getElementById('typeAffaire').addEventListener('change', function () {
        const prefix = this.options[this.selectedIndex].dataset.prefix;
        const rp     = document.querySelector('[name="numero_rp"]').value;
        const parts  = rp.split('/');
        document.getElementById('numeroRegistre').value = prefix
            ? `${prefix}/${parts[1]}/${parts[2]}`
            : '{{ $numbers["numero_registre"] }}';
    });

    // Ajout dynamique de parties
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