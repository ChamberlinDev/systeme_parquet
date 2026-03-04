@extends('admin.layout.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header text-white text-center py-4 bg-primary">
                    <h2 class="mb-2">Ajouter un utilisateur</h2>
                    <p class="mb-0 small">Système de Gestion des Dossiers Judiciaires</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{route('users.register')}}" method="POST">
                        @csrf

                        {{-- Nom et Email --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Mot de passe et Rôle --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" required>
                                    <option value="">-- Sélectionner un rôle --</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Statut du compte --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Statut du compte <span class="text-danger">*</span></label>
                                <select name="is_actif" class="form-control" required>
                                    <option value="1" {{ old('is_actif', 1) == 1 ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ old('is_actif') === "0" ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('is_actif')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parquet <span class="text-danger">*</span></label>
                                <select name="parquet_id" class="form-control" required>
                                    <option value="">-- Sélectionner un parquet --</option>
                                    @foreach ($parquets as $parquet)
                                    <option value="{{ $parquet->id }}"
                                        {{ old('parquet_id') == $parquet->id ? 'selected' : '' }}>
                                        {{ $parquet->nom }} - {{ $parquet->ville }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('parquet_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <a href="/utilisateurs" class="btn btn-secondary mx-3">
                                Annuler
                            </a>
                            <button type="submit" class="btn text-white bg-primary" >
                                Ajouter l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection