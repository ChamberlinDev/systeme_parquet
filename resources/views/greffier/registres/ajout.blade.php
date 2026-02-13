@extends('greffier.layout.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Ajouter un registre</h3>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('registres.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">Nom du registre</label>
            <input type="text" name="nom" class="form-control" placeholder="Ex: Registre Correctionnel" required>
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code du registre</label>
            <input type="text" name="code" class="form-control" placeholder="Ex: CORR" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter le registre</button>
    </form>
</div>

@endsection