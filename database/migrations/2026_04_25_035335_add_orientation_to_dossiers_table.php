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
            $table->unsignedBigInteger('procureur_id')->nullable();
            $table->foreign('procureur_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('juge_id')->nullable();
            $table->foreign('juge_id')->references('id')->on('users')->onDelete('set null');
            $table->text('motif_orientation')->nullable();
            $table->timestamp('date_orientation')->nullable();
            $table->timestamp('date_audience')->nullable();
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
            $table->dropForeign(['juge_id']);
            $table->dropColumn(['juge_id', 'date_audience']);
        });
    }
};
