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
