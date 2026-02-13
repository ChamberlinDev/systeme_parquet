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
           $table->bigIncrements('id_dossier');

            $table->string('num_dossier')->unique();
            $table->date('date_enregistrement');

            $table->enum('type_affaire', [
                'correctionnelle',
                'criminelle',
                'civile',
                'sociale',
                'referé'
            ]);

            $table->enum('statut', ['encours','classé','jugé','executé','archivé' ])->default('encours');

            // CLÉS ÉTRANGÈRES SANS AFTER
            $table->unsignedBigInteger('id_parquet');
            $table->unsignedBigInteger('id_greffier');
            // Foreign keys
            $table->foreign('id_parquet')
                  ->references('id_parquet')
                  ->on('parquets')
                  ->onDelete('restrict');
            $table->foreign('id_greffier')
                  ->references('id_greffier')
                  ->on('greffiers')
                  ->onDelete('restrict');

            $table->timestamps();
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
