<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParquetController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RegistreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'loginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(['auth'])->group(function () {

    // changement mot de passe autorisé AVANT le middleware force
    Route::get('/change_password', [LoginController::class, 'change_password_form'])
        ->name('change_password.form');

    Route::post('/change_password', [LoginController::class, 'change_password'])
        ->name('change_password');

    Route::middleware(['force.password.change'])->group(function () {

        // Déconnexion
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        /*
        |--------------------------------------------------------------------------
        | ACCUEILS
        |--------------------------------------------------------------------------
        */
        Route::get('/accueil_admin', [HomeController::class, 'accueil_admin'])->name('accueil.admin');
        Route::get('/accueil_greffier', [HomeController::class, 'accueil_greffier'])->name('accueil.greffier');


        /*
        |--------------------------------------------------------------------------
        | DOSSIERS
        |--------------------------------------------------------------------------
        */
        Route::get('/dossiers', [DossierController::class, 'index'])->name('dossiers.index');
        Route::get('/greffier/dossiers', [DossierController::class, 'index_greffier'])->name('dossiers.index.greffier');
        Route::get('/greffier/dossiers/{id}', [DossierController::class, 'show'])->name('dossiers.show');


        // creation dossier
        Route::get('/dossiers/creer', [DossierController::class, 'create_form'])->name('dossiers.create.form');
        Route::post('/dossiers/creer', [DossierController::class, 'store'])->name('dossiers.store');

        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */
        Route::get('/profil', [ProfilController::class, 'profil_view'])->name('profil.voir');

        /*
        |--------------------------------------------------------------------------
        | UTILISATEURS
        |--------------------------------------------------------------------------
        */
        Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
        Route::get('/create_user', [UserController::class, 'create_user_form'])->name('users.create');
        Route::post('/register', [UserController::class, 'register'])->name('users.register');
        Route::patch('/users/{id}/activer', [UserController::class, 'activer'])->name('users.activer');
        Route::patch('/users/{id}/desactiver', [UserController::class, 'desactiver'])->name('users.desactiver');


        Route::get('liste_parquets', [ParquetController::class, 'index'])->name('parquets.index');
        Route::get('/parquets', [ParquetController::class, 'create']);
        Route::post('/parquet/creer', [ParquetController::class, 'store'])->name('parquets.store');

        Route::resource('registres', RegistreController::class);
    });
});
