<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de mot de passe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card shadow-lg">
                <div class="card-header text-white text-center py-4"
                     style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h4 class="mb-1">Changement obligatoire</h4>
                    <p class="mb-0 small">
                        Pour des raisons de sécurité, veuillez définir un nouveau mot de passe
                    </p>
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

                    <form action="{{ route('change_password') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Nouveau mot de passe
                            </label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control"
                                   placeholder="Minimum 8 caractères"
                                   required
                                   autocomplete="new-password">
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirmer le mot de passe
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   placeholder="Répétez le mot de passe"
                                   required
                                   autocomplete="new-password">
                        </div>

                        <button type="submit"
                                class="btn text-white w-100 fw-semibold"
                                style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            Valider le nouveau mot de passe
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
