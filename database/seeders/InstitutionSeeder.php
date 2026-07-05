<?php

namespace Database\Seeders;

use App\Models\Institution_executante;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = [
            ['nom_institution' => 'Administration pénitentiaire', 'type_institution' => 'penitentiaire'],
            ['nom_institution' => 'Trésor public',                'type_institution' => 'tresor'],
            ['nom_institution' => 'Huissiers de justice',         'type_institution' => 'huissier'],
        ];

        foreach ($institutions as $data) {
            Institution_executante::firstOrCreate(['nom_institution' => $data['nom_institution']], $data);
        }
    }
}
