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
            $table->id('id_registre');
            $table->string('code')->unique(); // CORR, CRIM, CIV...
            $table->string('nom');            // Correctionnelle, Criminelle...
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
