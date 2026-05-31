<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->text('motif_archivage')->nullable()->after('id_procureur');
            $table->date('date_archivage')->nullable()->after('motif_archivage');
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn(['motif_archivage', 'date_archivage']);
        });
    }
};
