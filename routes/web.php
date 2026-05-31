<?php

use App\Http\Controllers\ActeInstructionController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ArchivageController;
use App\Http\Controllers\AudienceController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\MessageInstructionController;
use App\Http\Controllers\MesureDetentionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ExecutionController;
use App\Http\Controllers\StatistiqueController;
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
        Route::get('/accueil_substitut', [HomeController::class, 'accueil_substitut'])->name('accueil.substitut');
        Route::get('/accueil_juge', [HomeController::class, 'accueil_juge'])->name('accueil.juge');
        Route::get('/accueil_pj', [HomeController::class, 'accueil_pj'])->name('accueil.pj');
        Route::get('/accueil_huissier', [HomeController::class, 'accueil_huissier'])->name('accueil.huissier');
        Route::get('/accueil_penitentiaire', [HomeController::class, 'accueil_penitentiaire'])->name('accueil.penitentiaire');
        Route::get('/accueil_tresor', [HomeController::class, 'accueil_tresor'])->name('accueil.tresor');
        

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

        Route::get('/dossiers/{id}/edit', [DossierController::class, 'edit'])->name('dossiers.edit');
        Route::put('/dossiers/{id}', [DossierController::class, 'update'])->name('dossiers.update');
        Route::delete('/dossiers/{id}', [DossierController::class, 'destroy'])->name('dossiers.destroy');

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

        Route::get('/procureur/dossiers/{dossier}/acte-pdf', [ParquetController::class, 'genererActePdf'])
            ->whereNumber('dossier')
            ->name('dossiers.acte.pdf');

        Route::post('/procureur/dossiers/{dossier}/acte-signe', [ParquetController::class, 'uploadActeSigne'])
            ->whereNumber('dossier')
            ->name('dossiers.acte.upload');

        Route::get('/dossiers/{dossier}/acte-signe', [ParquetController::class, 'voirActeSigne'])
            ->whereNumber('dossier')
            ->name('dossiers.acte.voir');

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

        /*
        |--------------------------------------------------------------------------
        | EXÉCUTIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/greffier/executions', [ExecutionController::class, 'index'])->name('executions.index');

        Route::get('/decisions/{decision}/executions/creer', [ExecutionController::class, 'create'])
            ->whereNumber('decision')
            ->name('executions.create');

        Route::post('/decisions/{decision}/executions/creer', [ExecutionController::class, 'store'])
            ->whereNumber('decision')
            ->name('executions.store');

        Route::get('/executions/{execution}', [ExecutionController::class, 'show'])
            ->whereNumber('execution')
            ->name('executions.show');

        Route::patch('/executions/{execution}/statut', [ExecutionController::class, 'updateStatut'])
            ->whereNumber('execution')
            ->name('executions.statut');

        /*
        |--------------------------------------------------------------------------
        | ARCHIVAGE
        |--------------------------------------------------------------------------
        */
        Route::get('/greffier/archives', [ArchivageController::class, 'index'])->name('archivage.index');

        Route::get('/dossiers/{dossier}/archiver', [ArchivageController::class, 'confirmer'])
            ->whereNumber('dossier')
            ->name('archivage.confirmer');

        Route::post('/dossiers/{dossier}/archiver', [ArchivageController::class, 'store'])
            ->whereNumber('dossier')
            ->name('archivage.store');

        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */
        Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index');

        /*
        |--------------------------------------------------------------------------
        | JOURNAL D'AUDIT (admin uniquement)
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/audit', [AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/admin/audit/export', [AuditLogController::class, 'export'])->name('audit.export');

        /*
        |--------------------------------------------------------------------------
        | INSTRUCTION JUDICIAIRE
        |--------------------------------------------------------------------------
        */
        Route::get('/juge/instructions', [InstructionController::class, 'indexJuge'])->name('instructions.index.juge');
        Route::get('/procureur/instructions', [InstructionController::class, 'indexProcureur'])->name('instructions.index.procureur');

        Route::get('/dossiers/{dossier}/instruction/ouvrir', [InstructionController::class, 'create'])
            ->whereNumber('dossier')->name('instructions.create');
        Route::post('/dossiers/{dossier}/instruction/ouvrir', [InstructionController::class, 'store'])
            ->whereNumber('dossier')->name('instructions.store');

        Route::get('/instructions/{instruction}', [InstructionController::class, 'show'])
            ->name('instructions.show');
        Route::post('/instructions/{instruction}/clore', [InstructionController::class, 'clore'])
            ->name('instructions.clore');

        // Actes
        Route::post('/instructions/{instruction}/actes', [ActeInstructionController::class, 'store'])
            ->name('actes.store');
        Route::patch('/actes/{acte}/statut', [ActeInstructionController::class, 'updateStatut'])
            ->name('actes.statut');

        // Détention
        Route::post('/instructions/{instruction}/detention', [MesureDetentionController::class, 'store'])
            ->name('detention.store');
        Route::patch('/detentions/{detention}/prolonger', [MesureDetentionController::class, 'prolonger'])
            ->name('detention.prolonger');
        Route::patch('/detentions/{detention}/lever', [MesureDetentionController::class, 'lever'])
            ->name('detention.lever');

        // Messages
        Route::post('/instructions/{instruction}/messages', [MessageInstructionController::class, 'store'])
            ->name('messages.instruction.store');
    });
});
