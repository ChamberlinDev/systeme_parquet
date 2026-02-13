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
        Schema::create('dossier_audience', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dossier');
            $table->unsignedBigInteger('id_audience');
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
            $table->foreign('id_audience')->references('id_audience')->on('audiences')->onDelete('cascade');

            // Empêche doublons
            $table->unique(['id_dossier', 'id_audience']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_audience');
    }
};
