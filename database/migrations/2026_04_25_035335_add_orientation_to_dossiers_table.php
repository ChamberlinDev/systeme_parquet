<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('procureur_id')->nullable()->after('statut');
            $table->foreign('procureur_id')->references('id')->on('users')->onDelete('set null');
            $table->text('motif_orientation')->nullable()->after('procureur_id');
            $table->timestamp('date_orientation')->nullable()->after('motif_orientation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropForeign(['procureur_id']);
            $table->dropColumn(['procureur_id', 'motif_orientation', 'date_orientation']);
        });
    }
};
