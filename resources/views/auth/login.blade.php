<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{{ asset('img/logo/logo.png') }}" rel="icon">
    <title>GS. Parquet - Connexion</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ruang-admin.min.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-login">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-9 col-md-6">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="login-form p-4">

                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900 mb-2">Connexion</h1>
                                        <p class="text-gray-500">
                                            Système de gestion et de suivi des dossiers au parquet
                                        </p>
                                    </div>
                                    <!-- message deconnexion -->
                                    @if (session('success'))
                                    <div class="alert alert-danger">
                                        {{ session('success') }}
                                    </div>
                                    @endif 


                                    {{-- ===== Messages d'erreurs ===== --}}
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    {{-- ===== Formulaire ===== --}}
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        {{-- Email --}}
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input
                                                type="email"
                                                name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}"
                                                placeholder="Entrez votre adresse email"
                                                required
                                                autofocus>
                                        </div>

                                        {{-- Mot de passe --}}
                                        <div class="form-group">
                                            <label>Mot de passe</label>
                                            <input
                                                type="password"
                                                name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Entrez votre mot de passe"
                                                required>
                                        </div>

                                      

                                        {{-- Bouton connexion --}}
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-sign-in-alt"></i> Connexion
                                            </button>
                                        </div>

                                    </form>

                                    <hr>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/ruang-admin.min.js') }}"></script>

</body>

</html>