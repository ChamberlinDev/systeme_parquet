<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Documents déposés par la Police Judiciaire
        Schema::create('pj_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dossier');
            $table->unsignedBigInteger('id_instruction')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->enum('type_document', ['pv', 'photo', 'video', 'expertise', 'autre']);
            $table->string('description')->nullable();
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
            $table->foreign('id_instruction')->references('id')->on('instructions')->onDelete('set null');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Messagerie sécurisée inter-services
        Schema::create('messages_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expediteur_id');
            $table->enum('service_destinataire', [
                'parquet', 'greffe', 'pj', 'penitentiaire', 'tresor', 'juridiction',
            ]);
            $table->string('objet');
            $table->text('contenu');
            $table->unsignedBigInteger('id_dossier')->nullable();
            $table->boolean('lu')->default(false);
            $table->foreign('expediteur_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages_service');
        Schema::dropIfExists('pj_documents');
    }
};
