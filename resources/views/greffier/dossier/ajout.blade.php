@extends('greffier.layout.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Créer un nouveau dossier</h3>

    {{-- Messages de succès / erreurs --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('dossiers.create') }}" enctype="multipart/form-data">
        @csrf

        {{-- Registre --}}
        <div class="mb-3">
            <label for="id_registre" class="form-label">Registre</label>
            <select name="id_registre" id="id_registre" class="form-control" required>
                <option value="">-- Sélectionner un registre --</option>
                @foreach(\App\Models\Registre::all() as $registre)
                    <option value="{{ $registre->id }}">{{ $registre->nom }} ({{ $registre->code }})</option>
                @endforeach
            </select>
        </div>

        {{-- Type d'affaire --}}
        <div class="mb-3">
            <label for="type_affaire" class="form-label">Type d'affaire</label>
            <select name="type_affaire" class="form-control" required>
                <option value="">-- Type d'affaire --</option>
                <option value="correctionnelle">Correctionnelle</option>
                <option value="criminelle">Criminelle</option>
                <option value="civile">Civile</option>
                <option value="sociale">Sociale</option>
                <option value="referé">Référé</option>
            </select>
        </div>

        {{-- Statut --}}
        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" class="form-control" required>
                <option value="encours">En cours</option>
                <option value="classé">Classé</option>
                <option value="jugé">Jugé</option>
                <option value="executé">Exécuté</option>
                <option value="archivé">Archivé</option>
            </select>
        </div>

        {{-- Date de la demande --}}
        <div class="mb-3">
            <label for="date_demande" class="form-label">Date de la demande</label>
            <input type="date" name="date_demande" class="form-control" required>
        </div>

        {{-- Documents --}}
        <div class="mb-3">
            <label for="documents" class="form-label">Ajouter des documents</label>
            <input type="file" name="documents[]" multiple class="form-control">
            <small class="text-muted">Vous pouvez ajouter plusieurs fichiers.</small>
        </div>

        <button type="submit" class="btn btn-primary">Créer le dossier</button>
    </form>
</div>
@endsection
