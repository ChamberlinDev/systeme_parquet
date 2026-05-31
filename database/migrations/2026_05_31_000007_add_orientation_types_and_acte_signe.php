<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Étendre l'enum avec les 2 nouveaux types
        DB::statement("ALTER TABLE dossiers MODIFY COLUMN decision_orientation
            ENUM(
                'classement_sans_suite',
                'citation_directe',
                'comparution_immediate',
                'requisitoire_introductif',
                'mediation_penale',
                'renvoi_administratif'
            ) NULL");

        Schema::table('dossiers', function (Blueprint $table) {
            $table->string('acte_signe_path')->nullable()->after('date_archivage');
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn('acte_signe_path');
        });

        DB::statement("ALTER TABLE dossiers MODIFY COLUMN decision_orientation
            ENUM(
                'classement_sans_suite',
                'citation_directe',
                'comparution_immediate',
                'requisitoire_introductif'
            ) NULL");
    }
};
