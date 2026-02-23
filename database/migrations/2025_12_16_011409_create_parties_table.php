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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dossier'); // doit être unsignedBigInteger
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact');
            $table->enum('role', ['Plaignant', 'Défendeur', 'Témoin'])->default('Plaignant');
            $table->timestamps();

            $table->foreign('id_dossier')
                ->references('id_dossier')->on('dossiers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
