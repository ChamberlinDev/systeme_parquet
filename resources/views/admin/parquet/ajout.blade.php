@extends('admin.layout.app')
@section('content')

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Créer un parquet</h4>
            <p class="mb-0 small text-center">Systeme de Gestion des Dossiers Judicaires</p>
        </div>
        <div class="card-body">
            <form action="{{ route('parquets.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label>Nom du Parquet</label>
                            <input type="text" name="nom" class="form-control" placeholder="Entrez le nom du parquet" required>
                        </div>

                        <div class="col-6">
                            <label>Ville</label>
                            <input type="text" name="ville" class="form-control" placeholder="Entrez la ville" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label>Adresse</label>
                            <input type="text" name="adresse" placeholder="Entrez le quartier" class="form-control">
                        </div>

                        <div class="col-6">
                            <label>Téléphone</label>
                            <input type="text" name="telephone" placeholder="Entrez le numero de telephone" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Entrez l'adresse email" class="form-control">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Enregistrer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection