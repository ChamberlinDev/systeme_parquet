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
            $table->bigIncrements('id_partie'); // PK
            $table->unsignedBigInteger('id_dossier'); // FK
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('contact')->nullable();
            $table->enum('qualite', ['prevenu', 'plaignant', 'temoin', 'avocat']);
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
            $table->timestamps();
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
