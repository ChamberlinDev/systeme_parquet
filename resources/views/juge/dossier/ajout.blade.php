@extends('juge.layout.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card mb-4 shadow-sm">
            <div class="card-header py-3 text-center">
                <h2 class="m-0 font-weight-bold text-dark">Ajouter un Dossier</h2>
            </div>
            <div class="card-body p-5 text-dark">
                <form action="{{ route('dossiers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label>Numero RP <small class="text-muted">(genere automatiquement)</small></label>
                            <input type="text" class="form-control bg-light" name="numero_rp"
                                value="{{ $numbers['numero_rp'] }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label>Numero dossier <small class="text-muted">(genere automatiquement)</small></label>
                            <input type="text" class="form-control bg-light" name="numero_registre"
                                id="numeroRegistre" value="{{ $numbers['numero_registre'] }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label>Type d'affaire <span class="text-danger">*</span></label>
                            <select class="form-control" name="id_registre" id="typeAffaire" required>
                                <option value="">-- Selectionner --</option>
                                @foreach($registres as $registre)
                                <option value="{{ $registre->id_registre }}"
                                    data-prefix="{{ $registre->code }}"
                                    {{ old('id_registre') == $registre->id_registre ? 'selected' : '' }}>
                                    {{ $registre->nom }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_registre') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Date de la demande <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date_demande') is-invalid @enderror"
                                name="date_demande" value="{{ old('date_demande', date('Y-m-d')) }}" required>
                            @error('date_demande') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Parquet competent</label>
                            <input type="text" class="form-control" name="parquet_competent"
                                placeholder="Ex : Parquet de Dakar" value="{{ old('parquet_competent') }}">
                        </div>

                        <div class="col-md-6">
                            <label>Juge responsable</label>
                            <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                        </div>

                        <div class="col-md-12">
                            <label>Nature de l'infraction</label>
                            <textarea class="form-control" name="nature_infraction" rows="3"
                                placeholder="Decrivez les faits...">{{ old('nature_infraction') }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="text-dark">Parties</h5>
                    <div id="partiesContainer">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <input type="text" name="parties[0][nom]" class="form-control" placeholder="Nom *" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="parties[0][prenom]" class="form-control" placeholder="Prenom">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="parties[0][contact]" class="form-control" placeholder="Contact">
                            </div>
                            <div class="col-md-3">
                                <select name="parties[0][role]" class="form-control">
                                    <option value="Plaignant">Plaignant</option>
                                    <option value="Defendeur">Defendeur</option>
                                    <option value="Temoin">Temoin</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-secondary mb-4" id="addPartie">
                        <i class="fas fa-plus"></i> Ajouter une partie
                    </button>

                    <hr class="my-4">

                    <h5 class="text-dark">Pieces jointes</h5>
                    <input type="file" class="form-control @error('pdf_files') is-invalid @enderror"
                        name="pdf_files[]" accept="application/pdf" multiple>
                    @error('pdf_files') <small class="text-danger">{{ $message }}</small> @enderror

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-save me-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let partieIndex = 1;

    document.getElementById('typeAffaire').addEventListener('change', function() {
        const prefix = this.options[this.selectedIndex].dataset.prefix;
        const rp = document.querySelector('[name="numero_rp"]').value;
        const parts = rp.split('/');

        document.getElementById('numeroRegistre').value = prefix
            ? `${prefix}/${parts[1]}/${parts[2]}`
            : '{{ $numbers["numero_registre"] }}';
    });

    document.getElementById('addPartie').addEventListener('click', function() {
        const container = document.getElementById('partiesContainer');
        const row = document.createElement('div');
        row.classList.add('row', 'g-3', 'mb-3');

        row.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="parties[${partieIndex}][nom]" class="form-control" placeholder="Nom *" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="parties[${partieIndex}][prenom]" class="form-control" placeholder="Prenom">
            </div>
            <div class="col-md-3">
                <input type="text" name="parties[${partieIndex}][contact]" class="form-control" placeholder="Contact">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <select name="parties[${partieIndex}][role]" class="form-control">
                    <option value="Plaignant">Plaignant</option>
                    <option value="Defendeur">Defendeur</option>
                    <option value="Temoin">Temoin</option>
                </select>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.row').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        container.appendChild(row);
        partieIndex++;
    });
</script>
@endsection
