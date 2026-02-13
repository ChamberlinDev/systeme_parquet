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
        Schema::create('decisions', function (Blueprint $table) {
            $table->bigIncrements('id_decision');
            $table->unsignedBigInteger('id_audience');
            $table->enum('type_decision', ['jugement', 'ordonnance', 'arret']);
            $table->longText('contenu');
            $table->date('date_decision');
            $table->string('signatures');
            $table->foreign('id_audience')->references('id_audience')->on('audiences')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
