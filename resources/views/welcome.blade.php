@extends('admin.layout.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<h4 class="mb-4">Espace de travail</h4>
<div class="row">
    {{-- ================== TABLE UTILISATEURS ================== --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header  text-white bg-warning">
                <i class="fas fa-users">
                <strong>Liste des utilisateurs</strong>
                </i>
            </div>

            <div class="card-body">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary mb-3">
                    Ajouter un utilisateur
                </a>

                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
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
                                <span class="badge {{ $user->is_actif ? 'bg-success' : 'bg-danger' }} text-white">
                                    {{ $user->is_actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>

                            <td>
                                @foreach($user->getRoleNames() as $role)
                                <span class="badge bg-primary text-white">{{ ucfirst($role) }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                {{-- Modifier utilisateur --}}
                                <a href="#" class="btn btn-sm btn-warning" title="Modifier">
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
        </div>
    </div>

    {{-- ================== TABLE DOSSIERS ================== --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-folder">
                <strong>Liste des dossiers</strong>
                </i>
            </div>

            <div class="card-body">
                <a href="#" class="btn btn-sm btn-warning mb-3">
                    Ajouter un dossier
                </a>
                <table class="table table-bordered table-striped align-middle">
                    <thead>
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
                </table>
            </div>
        </div>
    </div>
</div>

@endsection