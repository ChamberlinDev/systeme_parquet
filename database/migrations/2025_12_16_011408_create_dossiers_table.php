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

        Schema::create('dossiers', function (Blueprint $table) {
            $table->id('id_dossier');
            $table->string('registre_rp');
            $table->string('type_affaire');
            $table->date('date_demande');
            $table->string('statut')->default('En cours');
            $table->unsignedBigInteger('id_greffier')->nullable();
            $table->unsignedBigInteger('id_parquet')->nullable();

            $table->timestamps();

            // Clé étrangère vers la table greffiers
            $table->foreign('id_greffier')->references('id_greffier')->on('greffiers')->onDelete('set null');
            // Clé étrangère vers la table parquets
            $table->foreign('id_parquet')->references('id_parquet')->on('parquets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
