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
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px; flex-shrink:0;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actifs --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Utilisateurs Actifs</div>
                        <div class="fs-4 fw-bold text-success">{{ $enCours }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px; flex-shrink:0;">
                        <i class="fas fa-user-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Inactifs --}}
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Utilisateurs Inactifs</div>
                        <div class="fs-4 fw-bold text-danger">{{ $traites }}</div>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px; flex-shrink:0;">
                        <i class="fas fa-user-times fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== EN-TÊTE ===================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark mb-0">Liste des utilisateurs</h4>
        <a href="/create_user" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Ajouter un utilisateur
        </a>
    </div>

    {{-- ===================== TABLEAU ===================== --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-uppercase text-muted small">
                        <th class="ps-4">#</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Parquet</th>
                        <th>Statut</th>
                        <th>Rôle(s)</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>

                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-semibold" style="width:38px; height:38px; flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold text-dark">{{ $user->name }}</span>
                            </div>
                        </td>

                        <td class="text-muted">
                            <i class="fas fa-envelope me-1 opacity-50"></i>{{ $user->email }}
                        </td>

                        <td>
                            @if($user->parquet)
                            <span class="badge bg-light border text-dark fw-normal">{{ $user->parquet->nom }}</span>
                            @else
                            <span class="text-muted fst-italic">Global</span>
                            @endif
                        </td>

                        <td>
                            @if($user->is_actif)
                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                <i class="fas fa-circle fa-xs me-1"></i>Actif
                            </span>
                            @else
                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                                <i class="fas fa-circle fa-xs me-1"></i>Inactif
                            </span>
                            @endif
                        </td>

                        <td>
                            @forelse($user->getRoleNames() as $role)
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 me-1">
                                {{ ucfirst($role) }}
                            </span>
                            @empty
                            <span class="text-muted fst-italic small">Aucun rôle</span>
                            @endforelse
                        </td>

                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Modifier --}}
                                <a href="{{ route('users.details', $user->id) }}" class="btn btn-sm btn-light text-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Supprimer --}}
                                <form action="{{ route('users.destroy', $user->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Supprimer">
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
                                        class="btn btn-sm btn-light {{ $user->is_actif ? 'text-secondary' : 'text-success' }}"
                                        title="{{ $user->is_actif ? 'Désactiver' : 'Activer' }}"
                                        onclick="return confirm('Confirmer cette action ?')">
                                        <i class="fas {{ $user->is_actif ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-3x mb-3 opacity-25 d-block"></i>
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection