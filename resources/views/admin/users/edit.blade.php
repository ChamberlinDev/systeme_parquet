@extends('admin.layout.app')
@section('content')
<div class="container-fluid py-4">


    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="row g-4 mb-4">





                <hr>

                {{-- HEADER --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <h4 class="fw-bold mb-1">
                                    <i class="fas fa-user-plus text-primary me-2"></i>Modification de l'utilisateur
                                </h4>
                                <p class="text-muted mb-0 small">Système de Gestion des Dossiers Judiciaires</p>
                            </div>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ÉTAPE 1 : IDENTITÉ --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <span class="badge bg-primary rounded-circle me-2">1</span>
                                Identité
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">
                                        Nom complet <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{$user->name}}"
                                            placeholder="Prénom et nom"
                                            required>
                                    </div>
                                    @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">
                                        Adresse email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{$user->email}}"
                                            placeholder="exemple@email.com"
                                            required>
                                    </div>
                                    @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">
                                        Mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Minimum 8 caractères"
                                            required value="{{$user->password}}">
                                    </div>
                                    @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        L'utilisateur devra changer son mot de passe à la première connexion.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">
                                        Statut du compte <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-toggle-on text-muted"></i>
                                        </span>
                                        <select name="is_actif"
                                            class="form-select @error('is_actif') is-invalid @enderror"
                                            required>
                                            <option value="1" {{ old('is_actif', 1) == 1 ? 'selected' : '' }}>
                                                Actif
                                            </option>
                                            <option value="0" {{ old('is_actif') === "0" ? 'selected' : '' }}>
                                                Inactif
                                            </option>
                                        </select>
                                    </div>
                                    @error('is_actif')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ÉTAPE 2 : AFFECTATION --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <span class="badge bg-primary rounded-circle me-2">2</span>
                                Affectation & Rôle
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">
                                        Parquet <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-landmark text-muted"></i>
                                        </span>
                                        <select name="parquet_id"
                                            class="form-select @error('parquet_id') is-invalid @enderror"
                                            required>
                                            <option value="">— Sélectionner un parquet —</option>
                                            @foreach($parquets as $parquet)
                                            <option value="{{ $parquet->id }}"
                                                {{ old('parquet_id') == $parquet->id ? 'selected' : '' }}>
                                                {{ $parquet->nom }} — {{ $parquet->ville }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('parquet_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">
                                        Rôle <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-user-tag text-muted"></i>
                                        </span>
                                        <select name="role"
                                            class="form-select @error('role') is-invalid @enderror"
                                            required>
                                            <option value="">— Sélectionner un rôle —</option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->name }}"
                                                {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('role')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="d-flex gap-3 justify-content-end mb-5">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-user-plus me-2"></i> Modifier l'utilisateur
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection