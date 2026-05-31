<?php

use App\Http\Controllers\AudienceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DecisionController;
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
        Route::get('/accueil_procureur', [HomeController::class, 'accueil_procureur'])->name('accueil.procureur');
        Route::get('/accueil_juge', [HomeController::class, 'accueil_juge'])->name('accueil.juge');
        

        /*
        |--------------------------------------------------------------------------
        | DOSSIERS
        |--------------------------------------------------------------------------
        */
        Route::get('/dossiers', [DossierController::class, 'index'])->name('dossiers.index');
        Route::get('/greffier/dossiers', [DossierController::class, 'index_greffier'])->name('dossiers.index.greffier');
        Route::get('/procureur/dossiers', [DossierController::class, 'index_procureur'])->name('dossiers.index.procureur');
        Route::get('/juge/dossiers', [DossierController::class, 'index_juge'])->name('dossiers.index.juge');
        


        // creation dossier
        Route::get('/dossiers/creer', [DossierController::class, 'create_form'])->name('dossiers.create.form');
        Route::post('/dossiers/creer', [DossierController::class, 'store'])->name('dossiers.store');
        Route::get('/dossiers/{dossier}', [DossierController::class, 'show'])
            ->whereNumber('dossier')
            ->name('dossiers.show');
        Route::get('/dossiers/{dossier}/fichiers/{file}', [DossierController::class, 'showFile'])
            ->whereNumber('dossier')
            ->whereNumber('file')
            ->name('dossiers.files.show');

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

        Route::resource('registres', RegistreController::class);

        /*
        |--------------------------------------------------------------------------
        | ORIENTATION PARQUET
        |--------------------------------------------------------------------------
        */
        Route::get('/procureur/dossiers/{dossier}/orientation', [ParquetController::class, 'orientationForm'])
            ->whereNumber('dossier')
            ->name('dossiers.orientation.form');

        Route::post('/procureur/dossiers/{dossier}/orientation', [ParquetController::class, 'orientationStore'])
            ->whereNumber('dossier')
            ->name('dossiers.orientation.store');

        /*
        |--------------------------------------------------------------------------
        | AUDIENCES
        |--------------------------------------------------------------------------
        */
        Route::get('/greffier/audiences', [AudienceController::class, 'indexGreffier'])->name('audiences.index.greffier');
        Route::get('/juge/audiences', [AudienceController::class, 'indexJuge'])->name('audiences.index.juge');
        Route::get('/procureur/audiences', [AudienceController::class, 'indexProcureur'])->name('audiences.index.procureur');

        Route::get('/audiences/creer', [AudienceController::class, 'create'])->name('audiences.create');
        Route::post('/audiences/creer', [AudienceController::class, 'store'])->name('audiences.store');

        Route::get('/audiences/{audience}', [AudienceController::class, 'show'])
            ->whereNumber('audience')
            ->name('audiences.show');

        Route::post('/audiences/{audience}/pv', [AudienceController::class, 'addPv'])
            ->whereNumber('audience')
            ->name('audiences.pv');

        /*
        |--------------------------------------------------------------------------
        | DECISIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/audiences/{audience}/dossiers/{dossier}/decision', [DecisionController::class, 'create'])
            ->whereNumber('audience')->whereNumber('dossier')
            ->name('decisions.create');

        Route::post('/audiences/{audience}/dossiers/{dossier}/decision', [DecisionController::class, 'store'])
            ->whereNumber('audience')->whereNumber('dossier')
            ->name('decisions.store');
    });
});
