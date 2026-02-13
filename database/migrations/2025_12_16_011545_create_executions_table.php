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
        Schema::create('executions', function (Blueprint $table) {
          $table->bigIncrements('id_execution');

            // Clés étrangères : types exactement BIGINT UNSIGNED
            $table->unsignedBigInteger('id_decision');
            $table->unsignedBigInteger('id_institution');

            $table->enum('type_peine', [
                'privative_liberte',
                'pecuniaire',
                'complementaire'
            ])->notNull();

            $table->date('date_execution')->nullable();

            $table->enum('statut_execution', [
                'en_cours',
                'executee',
                'non_executee'
            ])->default('en_cours');


            // Définition des clés étrangères
            $table->foreign('id_decision')
                  ->references('id_decision')
                  ->on('decisions')
                  ->onDelete('cascade');

            $table->foreign('id_institution')
                  ->references('id_institution')
                  ->on('institution_executantes')
                  ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('executions');
    }
};
