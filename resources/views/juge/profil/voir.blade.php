@extends('juge.layout.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- HEADER --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                    justify-content-center fw-bold fs-4"
                             style="width:56px;height:56px">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ auth()->user()->name }}</h4>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                            <div class="mt-1 d-flex gap-2 flex-wrap">
                                @foreach(auth()->user()->getRoleNames() as $role)
                                    <span class="badge bg-primary">{{ ucfirst($role) }}</span>
                                @endforeach
                                @if(auth()->user()->is_actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
            @endif

            <form action="#" method="POST">
                @csrf
                @method('PUT')

                {{-- ÉTAPE 1 : INFORMATIONS PERSONNELLES --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <span class="badge bg-primary rounded-circle me-2">1</span>
                            Informations personnelles
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nom complet</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', auth()->user()->name) }}"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Adresse email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Parquet</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-landmark text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ auth()->user()->parquet->nom ?? '—' }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Membre depuis</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light"
                                           value="{{ auth()->user()->created_at->format('d/m/Y') }}" readonly>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ÉTAPE 2 : CHANGER LE MOT DE PASSE --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <span class="badge bg-primary rounded-circle me-2">2</span>
                            Changer le mot de passe
                            <span class="text-muted fw-normal small ms-1">(laisser vide pour ne pas modifier)</span>
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Mot de passe actuel</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           placeholder="Votre mot de passe actuel">
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Minimum 8 caractères">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Confirmation</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" name="password_confirmation"
                                           class="form-control"
                                           placeholder="Répétez le nouveau mot de passe">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex gap-3 justify-content-end mb-5">
                    <a href="/accueil_admin" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection