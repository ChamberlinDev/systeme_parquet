<?php

namespace Database\Seeders;

use App\Models\Registre;
use Illuminate\Database\Seeder;

class RegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registres = [
            ['code' => 'CORR', 'nom' => 'Correctionnelle'],
            ['code' => 'CRIM', 'nom' => 'Criminelle'],
            ['code' => 'CIV', 'nom' => 'Civile'],
            ['code' => 'SOC', 'nom' => 'Sociale'],
            ['code' => 'REF', 'nom' => 'Référé'],
        ];

        foreach ($registres as $registre) {
            Registre::updateOrCreate(
                ['code' => $registre['code']],
                ['nom' => $registre['nom']]
            );
        }
    }
}
