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
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id_document');
            $table->unsignedBigInteger('id_dossier');
            $table->string('nom');
            $table->string('chemin_fichier');
            $table->string('type_document')->nullable();
            $table->timestamps();
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
