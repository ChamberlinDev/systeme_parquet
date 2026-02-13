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
        Schema::create('audiences', function (Blueprint $table) {
            $table->bigIncrements('id_audience'); // PK

            $table->date('date_audience');
            $table->string('salle');

            $table->enum('type_audience', ['correctionnelle', 'criminelle', 'sociale', 'refere']);

            // rôle = liste des affaires (description libre)
            $table->text('role')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiences');
    }
};
