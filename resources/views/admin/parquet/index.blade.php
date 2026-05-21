@extends('admin.layout.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid py-4">

    {{-- ===================== STATISTIQUES ===================== --}}
    <div class="row g-4 mb-4">

        @php
        $total = $parquets->count();
        @endphp

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Total parquets</div>
                        <div class="fs-4 fw-bold text-dark">{{ $total }}</div>
                    </div>
                    <i class="fas fa-home fa-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>

        

    </div>

    <hr>


    <h3 class="text-center">Liste des parquets</h3>

    <hr>

    <a href="/parquets" class="btn btn-primary mb-3">
        Ajouter un parquet
    </a>

    <div>
        @if(session('success'))
        <div class="alert alert-success text-dark">
            {{ session('success') }}
        </div>
        @endif
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-white">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Ville</th>
                <th>Adresse</th>
                <th>Telephone</th>
                <th>Email</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($parquets as $parquet)
            <tr>
                <td>{{ $parquet->id }}</td>

                <td>{{ $parquet->nom }}</td>

                <td>{{ $parquet->ville }}</td>

                <td>
                   {{$parquet->adresse}}
                </td>

                <td>
                    {{$parquet->telephone}}
                </td>
                 <td>
                    {{$parquet->email}}
                </td>

                <td class="text-center">
                    {{-- Modifier parquet --}}
                    <a href="{{route('parquets.edit', $parquet->id)}}" class="btn btn-sm btn-warning" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>

                    {{-- Supprimer parquet --}}
                    <form action="{{ route('parquets.destroy', $parquet->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Voulez-vous vraiment supprimer cet parquet ?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>

                   
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Aucun parquet trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection