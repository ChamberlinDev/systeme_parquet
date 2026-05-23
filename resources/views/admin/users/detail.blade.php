@extends('admin.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- HEADER --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                        justify-content-center fw-bold fs-4"
                                 style="width:56px;height:56px">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($user->is_actif)
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-circle me-1" style="font-size:8px"></i> Actif
                                </span>
                            @else
                                <span class="badge bg-danger fs-6 px-3 py-2">
                                    <i class="fas fa-circle me-1" style="font-size:8px"></i> Inactif
                                </span>
                            @endif
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- INFORMATIONS GÉNÉRALES --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-user text-primary me-2"></i>Informations générales
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <small class="text-muted fw-semibold text-uppercase">Nom complet</small>
                            <div class="fw-semibold mt-1">{{ $user->name }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted fw-semibold text-uppercase">Adresse email</small>
                            <div class="fw-semibold mt-1">{{ $user->email }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted fw-semibold text-uppercase">Rôle</small>
                            <div class="mt-1">
                                @forelse($user->getRoleNames() as $role)
                                    <span class="badge bg-primary">{{ ucfirst($role) }}</span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted fw-semibold text-uppercase">Parquet</small>
                            <div class="fw-semibold mt-1">
                                {{ $user->parquet->nom ?? '—' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted fw-semibold text-uppercase">Membre depuis</small>
                            <div class="fw-semibold mt-1">
                                {{ $user->created_at->format('d/m/Y') }}
                                <small class="text-muted ms-1">
                                    ({{ $user->created_at->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted fw-semibold text-uppercase">Dernière modification</small>
                            <div class="fw-semibold mt-1">
                                {{ $user->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- PERMISSIONS --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-shield-alt text-primary me-2"></i>Permissions
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $permissions = $user->getAllPermissions()->groupBy(fn($p) => explode('.', $p->name)[0]);
                    @endphp

                    @forelse($permissions as $groupe => $perms)
                        <div class="mb-3">
                            <small class="text-muted fw-semibold text-uppercase d-block mb-2">
                                <i class="fas fa-layer-group me-1"></i>{{ $groupe }}
                            </small>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($perms as $perm)
                                    <span class="badge bg-light border text-dark px-3 py-2">
                                        <i class="fas fa-check text-success me-1"></i>
                                        {{ $perm->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Aucune permission attribuée.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="card shadow-sm border-0 mb-5">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-cogs text-primary me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2">

                        <a href="#"
                           class="btn btn-outline-primary">
                            <i class="fas fa-user-shield me-1"></i> Gérer les permissions
                        </a>
                        <a href="{{route('users.edit', $user->id)}}"
                           class="btn btn-outline-warning">
                            <i class="fas fa-user-edit me-1"></i> Modifier
                        </a>

                        @if($user->is_actif)
                            <form action="{{ route('users.desactiver', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-outline-danger"
                                        onclick="return confirm('Désactiver cet utilisateur ?')">
                                    <i class="fas fa-ban me-1"></i> Désactiver
                                </button>
                            </form>
                        @else
                            <form action="{{ route('users.activer', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-outline-success">
                                    <i class="fas fa-check-circle me-1"></i> Activer
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection