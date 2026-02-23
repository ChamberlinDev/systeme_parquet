<?php

namespace Database\Seeders;

use App\Models\Registre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Registre::insert([
            ['code' => 'CORR', 'nom' => 'Correctionnelle'],
            ['code' => 'CRIM', 'nom' => 'Criminelle'],
            ['code' => 'CIV',  'nom' => 'Civile'],
            ['code' => 'SOC',  'nom' => 'Sociale'],
            ['code' => 'REF',  'nom' => 'Référé'],
        ]);
    }
}
