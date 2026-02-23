@extends('greffier.layout.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card mb-4 shadow-sm" style="min-width: 100%; max-width: 100%;">
            <div class="card-header py-3 text-center">
                <h2 class="m-0 font-weight-bold text-dark">Ajouter un dossier</h2>
            </div>
            <hr>
            <div class="card-body p-5 text-dark">
                <form action="{{ route('dossiers.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Registre Parquet RP -->
                        <div class="col-md-6">
                            <label for="registreRP">Registre du Parquet</label>
                            <input type="text"
                                class="form-control"
                                id="registreRP"
                                name="registre_rp"
                                value="{{ $nextDossierRP }}"
                                readonly>
                            @error('registre_rp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Type d'affaire -->
                        <div class="col-md-6">
                            <label for="typeAffaire">Type d'affaire</label>
                            <select class="form-control @error('type_affaire') is-invalid @enderror"
                                id="typeAffaire"
                                name="type_affaire"
                                required>
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
                    </div>

                    <div class="row g-4 mt-2">
                        <!-- Date de la demande -->
                        <div class="col-md-6">
                            <label for="dateDemande">Date de la demande</label>
                            <input type="date"
                                class="form-control @error('date_demande') is-invalid @enderror"
                                id="dateDemande"
                                name="date_demande"
                                required>
                            @error('date_demande')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Parties (Plaignant) -->
                        <div class="col-md-6">
                            <label for="parties">Parties (Plaignant)</label>
                            <textarea
                                class="form-control @error('parties') is-invalid @enderror"
                                id="parties"
                                name="parties"
                                placeholder="Nom du plaignant"
                                required></textarea>
                            @error('parties')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <!-- Fichiers PDF -->
                        <div class="col-md-6">
                            <label for="pdfFiles">Pièces jointes (PDF)</label>
                            <input type="file"
                                class="form-control @error('pdf_files') is-invalid @enderror"
                                id="pdfFiles"
                                name="pdf_files[]"
                                accept="application/pdf"
                                multiple>
                            @error('pdf_files')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Nom du Greffier -->
                        <div class="col-md-6">
                            <label for="greffier">Nom du Greffier</label>
                            <input type="text"
                                class="form-control"
                                id="greffier"
                                value="{{ auth()->user()->name }}"
                                readonly>
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
@endsection
