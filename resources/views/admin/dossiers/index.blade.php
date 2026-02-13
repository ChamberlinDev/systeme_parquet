@extends('admin.layout.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid">
    <h3 class="text-center">Liste des dossiers</h3>
    <hr>

    <a href="/create_dossier" class="btn btn-primary mb-3">
        Ajouter un dossier
    </a>
    <table class="table table-bordered table-striped">
        <thead class="table-white">
            <tr>
                <th>#</th>
                <th>Numéro</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Aucun dossier disponible
                </td>
            </tr>
        </tbody>

</div>

@endsection