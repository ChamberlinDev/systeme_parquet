<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dossier')->nullable()->after('id_audience');
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->dropForeign(['id_dossier']);
            $table->dropColumn('id_dossier');
        });
    }
};
