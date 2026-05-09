<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS
        |--------------------------------------------------------------------------
        */
        $permissions = [

            // Dossier
            'dossier.create',
            'dossier.view',
            'dossier.update',
            'dossier.suivi',
            'dossier.archiver',

            // Analyse & Orientation
            // 'analyse.obv',
            // 'decision.orientation',

            // Rôle & Audience
            // 'role.prepare',
            // 'audience.tenir',

            // Décision
            // 'decision.rediger',
            // 'decision.signer',
            // 'decision.transmettre',

            // Statistiques
            // 'rapport.generer',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $greffier   = Role::firstOrCreate(['name' => 'greffier']);
        $juge       = Role::firstOrCreate(['name' => 'juge']);
        $procureur  = Role::firstOrCreate(['name' => 'procureur']);
        $substitut  = Role::firstOrCreate(['name' => 'substitut']);

        /*
        |--------------------------------------------------------------------------
        | ATTRIBUTION DES PERMISSIONS
        |--------------------------------------------------------------------------
        */

        // ADMIN → tout
        $admin->syncPermissions(Permission::all());

        // GREFFIER
         $greffier->syncPermissions([
            'dossier.create',
            'dossier.view',
            'dossier.update',
            'dossier.suivi',
            'decision.transmettre',
            'dossier.archiver',
            'rapport.generer',
        ]);

        // PROCUREUR
        // $procureur->syncPermissions([
        //     'dossier.view',
        //     'analyse.obv',
        //     'decision.orientation',
        //     'dossier.suivi',
        // ]);

        // SUBSTITUT
        // $substitut->syncPermissions([
        //     'dossier.view',
        //     'analyse.obv',
        //     'decision.orientation',
        //     'dossier.suivi',
        // ]);

        // JUGE
        // $juge->syncPermissions([
        //     'dossier.view',
        //     'role.prepare',
        //     'audience.tenir',
        //     'decision.rediger',
        //     'decision.signer',
        // ]); 

        //ADMIN PAR DÉFAUT

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('motdepasse1234'),
                'is_actif' => true,
                'must_change_password' => true, 
                'parquet_id'=>null,

            ]
        );

        $adminUser->assignRole('admin');
    }
}
