@extends('admin.layout.app')
@section('content')

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Modifier un parquet</h4>
            <p class="mb-0 small text-center">Systeme de Gestion des Dossiers Judicaires</p>
        </div>
        <div class="card-body">
            <form action="{{ route('parquets.update', $parquet->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label>Nom du Parquet</label>
                            <input type="text" name="nom" class="form-control" value="{{ $parquet->nom }}" required>
                        </div>

                        <div class="col-6">
                            <label>Ville</label>
                            <input type="text" name="ville" class="form-control" value="{{ $parquet->ville }}" placeholder="Entrez la ville" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label>Adresse</label>
                            <input type="text" name="adresse" class="form-control" value="{{ $parquet->adresse }}" placeholder="Entrez le quartier">
                        </div>

                        <div class="col-6">
                            <label>Téléphone</label>
                            <input type="text" name="telephone" class="form-control" value="{{ $parquet->telephone }}" placeholder="Entrez le numero de telephone">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $parquet->email }}" placeholder="Entrez l'adresse email">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">
                    Mettre à jour
                </button>
            </form>
        </div>
    </div>
</div>
@endsection