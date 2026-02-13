<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail Parquet - Connexion</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>


<body style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-lg">
                    <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                        <h2 class="mb-2">Connexion</h2>
                        <p class="mb-0 small">Système de Gestion des Dossiers Judiciaires</p>
                    </div>
                    <div class="card-body p-4">
                        {{-- Messages d'erreur --}}
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        <form action="login" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email" placeholder="Entrez votre identifiant">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Entrez votre mot de passe">
                            </div>

                            <!-- <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                                <a href="#" class="text-decoration-none">Mot de passe oublié ?</a>
                            </div> -->

                            <button type="submit" class="btn text-white w-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                Se connecter
                            </button>
                        </form>
                    </div>
                    <!-- <div class="card-footer text-center text-muted py-3">
                        <small>
                            © 2024 Tous droits réservés <br>
                            Accès réservé au personnel autorisé
                        </small>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>