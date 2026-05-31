<?php

namespace Database\Seeders;

use App\Models\Institution_executante;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = [
            ['nom' => 'Administration pénitentiaire', 'type_institution' => 'penitentiaire'],
            ['nom' => 'Trésor public',                'type_institution' => 'tresor'],
            ['nom' => 'Huissiers de justice',         'type_institution' => 'huissier'],
        ];

        foreach ($institutions as $data) {
            Institution_executante::firstOrCreate(['nom' => $data['nom']], $data);
        }
    }
}
