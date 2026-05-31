<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossier_historique', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dossier');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('detail')->nullable();
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_historique');
    }
};
