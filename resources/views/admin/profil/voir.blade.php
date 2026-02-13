@extends('admin.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-0">Gestion du profil</h3>
            <p class="text-muted">Gérez vos informations personnelles et votre mot de passe</p>
        </div>
    </div>

    <div class="row">
        {{-- Infos utilisateur --}}
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>

                    <h5 class="mb-2">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>

                    <div class="mb-3">
                        <span class="badge {{ auth()->user()->is_actif ? 'bg-success' : 'bg-danger' }} px-3 py-2 text-white">
                            {{ auth()->user()->is_actif ? 'Compte actif' : 'Compte inactif' }}
                        </span>
                    </div>

                    <div>
                        @foreach(auth()->user()->getRoleNames() as $role)
                            <span class="badge bg-primary px-3 py-2 me-1 text-white">
                                {{ ucfirst($role) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulaires --}}
        <div class="col-lg-8 col-md-7">
            {{-- Modifier informations --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>

                <div class="card-body p-4">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ auth()->user()->name }}"
                                    required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ auth()->user()->email }}"
                                    required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Changer mot de passe --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Changer le mot de passe</h5>
                </div>

                <div class="card-body p-4">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mot de passe actuel <span class="text-danger">*</span></label>
                            <input type="password"
                                name="current_password"
                                class="form-control"
                                required>
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nouveau mot de passe <span class="text-danger">*</span></label>
                                <input type="password"
                                    name="password"
                                    class="form-control"
                                    required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Confirmation <span class="text-danger">*</span></label>
                                <input type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-warning text-dark px-4">
                                Modifier le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection