@extends('admin.layout.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid py-4">

    {{-- ===================== STATISTIQUES ===================== --}}
    <div class="row g-4 mb-4">

        @php
        $total = $users->count();
        $enCours = $users->where('is_actif', 1)->count();
        $traites = $users->where('is_actif', 0)->count();
        @endphp

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Total Utilisateurs</div>
                        <div class="fs-4 fw-bold text-dark">{{ $total }}</div>
                    </div>
                    <i class="fas fa-users fa-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- En cours --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Utilisateurs Actifs</div>
                        <div class="fs-4 fw-bold text-success">{{ $enCours }}</div>
                    </div>
                    <i class="fas fa-user-check fa-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>

        {{-- Traités --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Utilisateurs Inactifs</div>
                        <div class="fs-4 fw-bold text-danger">{{ $traites }}</div>
                    </div>
                    <i class="fas fa-user-times fa-2x text-danger opacity-75"></i>
                </div>
            </div>
        </div>


    </div>

    <hr>


    <h3 class="text-center">Liste des utilisateurs</h3>

    <hr>

    <a href="/create_user" class="btn btn-primary mb-3">
        Ajouter un utilisateur
    </a>

    <table class="table table-bordered table-striped">
        <thead class="table-white">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Parquet</th>
                <th>Statut</th>
                <th>Rôle(s)</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>{{ $user->name }}</td>

                <td>{{ $user->email }}</td>
                <td>
                    @if($user->parquet)
                    {{ $user->parquet->nom }}
                    @else
                    <span class="text-muted">Global</span>
                    @endif
                </td>

                <td>
                    @if($user->is_actif)
                    <span class="badge bg-success text-white">Actif</span>
                    @else
                    <span class="badge bg-danger text-white">Inactif</span>
                    @endif
                </td>

                <td>
                    @forelse($user->getRoleNames() as $role)
                    <span class="badge bg-primary text-white">
                        {{ ucfirst($role) }}
                    </span>
                    @empty
                    <span class="text-muted">Aucun rôle</span>
                    @endforelse
                </td>

                <td class="text-center">
                    {{-- Modifier utilisateur --}}
                    <a href="{{ route('users.details', $user->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>

                    {{-- Supprimer utilisateur --}}
                    <form action="#"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>

                    {{-- Activer / Désactiver --}}
                    <form action="{{ $user->is_actif ? route('users.desactiver', $user->id) : route('users.activer', $user->id) }}"
                        method="POST"
                        class="d-inline">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="btn btn-sm {{ $user->is_actif ? 'btn-secondary' : 'btn-success' }}"
                            title="{{ $user->is_actif ? 'Désactiver' : 'Activer' }}"
                            onclick="return confirm('Confirmer cette action ?')">
                            @if($user->is_actif)
                            <i class="fas fa-user-slash"></i>
                            @else
                            <i class="fas fa-user-check"></i>
                            @endif
                        </button>
                    </form>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    Aucun utilisateur trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection