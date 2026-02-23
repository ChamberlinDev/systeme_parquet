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
            $table->string('numero_rp')->unique();
            $table->string('numero_registre')->unique();
            $table->unsignedBigInteger('id_registre');
            $table->foreign('id_registre')->references('id_registre')->on('registres')->onDelete('restrict');
            $table->text('nature_infraction')->nullable();
            $table->date('date_demande');
            $table->string('parquet_competent')->nullable();
            $table->string('statut')->default('En cours');
            $table->unsignedBigInteger('id_greffier')->nullable();
            $table->foreign('id_greffier')->references('id')->on('users')->onDelete('set null');
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
