@extends('greffier.layout.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Liste des registres</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('registres.create') }}" class="btn btn-primary mb-3">Ajouter un registre</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registres as $registre)
                <tr>
                    <td>{{ $registre->id }}</td>
                    <td>{{ $registre->nom }}</td>
                    <td>{{ $registre->code }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="#" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce registre ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
