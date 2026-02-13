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
                        <div class="logo-circle">⚖️</div>
                        <h2 class="mb-2">Inscription</h2>
                        <p class="mb-0 small">Système de Gestion des Dossiers Judiciaires</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="register" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rôle</label>
                                <select name="role" class="form-control" required>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn text-white w-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                S'inscrire
                            </button>
                           
                        </form>
                    </div>



                </div>

            </div>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>