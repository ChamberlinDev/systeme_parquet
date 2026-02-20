@extends('greffier.layout.app')

@section('content')

<div class="container mt-5">
    <article class="sign-up">

        <h2 class="sign-up__title">Gestion du profil</h2>

        {{-- Message succès --}}
        @if(session('success'))
            <div class="mb-3 text-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="sign-up-form form" action="#" method="POST">
            @csrf
            @method('PUT')

            {{-- ================= Informations personnelles ================= --}}
            <div class="row">

                <!-- Nom -->
                <div class="col-md-6 mb-3">
                    <label class="form-label-wrapper w-100">
                        <p class="form-label">Nom</p>
                        <input
                            class="form-input w-100 @error('name') is-invalid @enderror"
                            type="text"
                            name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            required>
                    </label>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label class="form-label-wrapper w-100">
                        <p class="form-label">Email</p>
                        <input
                            class="form-input w-100 @error('email') is-invalid @enderror"
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            required>
                    </label>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            {{-- ================= Sécurité ================= --}}
            <div class="row">

                <!-- Mot de passe actuel -->
                <div class="col-md-6 mb-3">
                    <label class="form-label-wrapper w-100">
                        <p class="form-label">Mot de passe actuel</p>
                        <input
                            class="form-input w-100 @error('current_password') is-invalid @enderror"
                            type="password"
                            name="current_password"
                            required>
                    </label>
                    @error('current_password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Statut & Rôle -->
                <div class="col-md-6 mb-3">
                    <p class="form-label">Statut & Rôle</p>

                    <div class="mb-2">
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

            <div class="row">

                <!-- Nouveau mot de passe -->
                <div class="col-md-6 mb-3">
                    <label class="form-label-wrapper w-100">
                        <p class="form-label">Nouveau mot de passe</p>
                        <input
                            class="form-input w-100 @error('password') is-invalid @enderror"
                            type="password"
                            name="password">
                    </label>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div class="col-md-6 mb-3">
                    <label class="form-label-wrapper w-100">
                        <p class="form-label">Confirmation</p>
                        <input
                            class="form-input w-100"
                            type="password"
                            name="password_confirmation">
                    </label>
                </div>

            </div>

            <div class="text-end mt-3">
                <button type="submit" class="form-btn primary-default-btn">
                    Mettre à jour
                </button>
            </div>

        </form>

    </article>
</div>

@endsection
