@extends('admin.layout.app')
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between text-center">
                    <h3 class="m-0 font-weight-bold text-primary ">Gestion du profil</h3>
                </div>

                <div class="card-body">

                    {{-- Message succès --}}
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            {{-- Nom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text"
                                        name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', auth()->user()->name) }}"
                                        required>
                                    @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', auth()->user()->email) }}"
                                        required>
                                    @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            {{-- Mot de passe actuel --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mot de passe actuel</label>
                                    <input type="password"
                                        name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        required>
                                    @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Statut + Rôle --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Statut</label>
                                    <div class="mb-2">
                                        <span class="badge {{ auth()->user()->is_actif ? 'badge-success' : 'badge-danger' }}">
                                            {{ auth()->user()->is_actif ? 'Compte actif' : 'Compte inactif' }}
                                        </span>
                                    </div>

                                    <label>Rôle</label>
                                    <div>
                                        @foreach(auth()->user()->getRoleNames() as $role)
                                        <span class="badge badge-primary mr-1">
                                            {{ ucfirst($role) }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            {{-- Nouveau mot de passe --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nouveau mot de passe</label>
                                    <input type="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirmation --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmation</label>
                                    <input type="password"
                                        name="password_confirmation"
                                        class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="text-right">
                            <a href="/accueil_admin" class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Mettre à jour
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection