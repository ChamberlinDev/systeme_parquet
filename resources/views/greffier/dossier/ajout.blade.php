@extends('greffier.layout.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card mb-4 shadow-sm" style="min-width: 100%; max-width: 100%;">
            <div class="card-header py-3 text-center">
                <h2 class="m-0 font-weight-bold text-dark">Ajouter un Dossier</h2>
            </div>
            <div class="card-body p-5 text-dark">

                <form action="{{ route('dossiers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- ======================== Compartment 1: Informations Dossier ======================== -->
                    <div class="mb-4">
                        <div class="row g-4">
                            <!-- Registre Parquet -->
                            <div class="col-md-6">
                                <label for="registreRP">Registre du Parquet</label>
                                <input type="text" class="form-control" id="registreRP" name="registre_rp"
                                    value="{{ $nextDossierRP }}" readonly>
                                @error('registre_rp')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Type d'affaire -->
                            <div class="col-md-6">
                                <label for="typeAffaire">Type d'affaire</label>
                                <select class="form-control @error('type_affaire') is-invalid @enderror" id="typeAffaire"
                                    name="type_affaire" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Correctionnelle">Correctionnelle</option>
                                    <option value="Criminelle">Criminelle</option>
                                    <option value="Civile">Civile</option>
                                    <option value="Sociale">Sociale</option>
                                    <option value="Referé">Referé</option>
                                </select>
                                @error('type_affaire')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Date de la demande -->
                            <div class="col-md-6">
                                <label for="dateDemande">Date de la demande</label>
                                <input type="date" class="form-control @error('date_demande') is-invalid @enderror"
                                    id="dateDemande" name="date_demande" required>
                                @error('date_demande')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Nom du greffier -->
                            <div class="col-md-6">
                                <label for="greffier">Greffier responsable</label>
                                <input type="text" class="form-control" id="greffier"
                                    value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- ======================== Compartment 2: Parties ======================== -->
                    <div class="mb-4">
                        <h5 class="text-dark">Parties</h5>
                        <div id="partiesContainer">
                            <div class="row g-3 mb-3 partie-row">
                                <div class="col-md-3">
                                    <input type="text" name="parties[0][nom]" class="form-control" placeholder="Nom" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="parties[0][prenom]" class="form-control" placeholder="Prénom">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="parties[0][contact]" class="form-control" placeholder="Contact">
                                </div>
                                <div class="col-md-3">
                                    <select name="parties[0][role]" class="form-control" required>
                                        <option value="Plaignant">Plaignant</option>
                                        <option value="Défendeur">Défendeur</option>
                                        <option value="Témoin">Témoin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info mb-4" id="addPartie"> + Ajouter une partie</button>
                    </div>

                    <hr class="my-4">

                    <!-- ======================== Compartment 3: Fichiers ======================== -->
                    <div class="mb-4">
                        <h5 class="text-dark">Fichiers PDF</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="pdfFiles">Ajouter des fichiers PDF</label>
                                <input type="file" class="form-control @error('pdf_files') is-invalid @enderror"
                                    id="pdfFiles" name="pdf_files[]" accept="application/pdf" multiple>
                                @error('pdf_files')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">Enregistrer</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script pour ajouter dynamiquement des parties avec role -->
<script>
    let partieIndex = 1;
    document.getElementById('addPartie').addEventListener('click', function() {
        const container = document.getElementById('partiesContainer');
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'g-3', 'mb-3', 'partie-row');
        newRow.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="parties[${partieIndex}][nom]" class="form-control" placeholder="Nom" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="parties[${partieIndex}][prenom]" class="form-control" placeholder="Prénom">
        </div>
        <div class="col-md-3">
            <input type="text" name="parties[${partieIndex}][contact]" class="form-control" placeholder="Contact">
        </div>
        <div class="col-md-3">
            <select name="parties[${partieIndex}][role]" class="form-control" required>
                <option value="Plaignant">Plaignant</option>
                <option value="Défendeur">Défendeur</option>
                <option value="Témoin">Témoin</option>
            </select>
        </div>
    `;
        container.appendChild(newRow);
        partieIndex++;
    });
</script>
@endsection