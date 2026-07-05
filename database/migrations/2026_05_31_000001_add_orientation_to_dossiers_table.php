<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->enum('decision_orientation', [
                'classement_sans_suite',
                'citation_directe',
                'comparution_immediate',
                'requisitoire_introductif',
            ])->nullable()->after('statut');

            // $table->text('motif_orientation')->nullable()->after('decision_orientation');
            // $table->date('date_orientation')->nullable()->after('motif_orientation');
            $table->unsignedBigInteger('id_procureur')->nullable()->after('date_orientation');
            $table->foreign('id_procureur')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropForeign(['id_procureur']);
            $table->dropColumn(['decision_orientation', 'motif_orientation', 'date_orientation', 'id_procureur']);
        });
    }
};
