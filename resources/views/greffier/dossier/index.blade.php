@extends('greffier.layout.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid">
    <h3 class="text-center">Liste des dossiers</h3>
    <hr>

    <a href="{{ route('dossiers.create.form') }}" class="btn btn-primary mb-3">
        Ajouter un dossier
    </a>
    <h3>Dossiers du parquet</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Numéro</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Greffier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dossiers as $dossier)
            <tr>
                <td>{{ $dossier->id_dossier }}</td>
                <td>{{ $dossier->num_dossier }}</td>
                <td>{{ ucfirst($dossier->type_affaire) }}</td>
                <td>{{ ucfirst($dossier->statut) }}</td>
                <td>{{ $dossier->date_enregistrement->format('d/m/Y') }}</td>
                <td>{{ $dossier->greffier->nom ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Aucun dossier disponible</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection