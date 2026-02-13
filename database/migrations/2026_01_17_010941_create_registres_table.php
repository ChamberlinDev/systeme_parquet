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
        Schema::create('registres', function (Blueprint $table) {
            $table->bigIncrements('id_registre');
            $table->string('code'); // CORR, CRIM, CIV, REF, CONTR
            $table->string('nom'); // Correctionnel, Criminel, Civil, Référé, Contraventions

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registres');
    }
};
