<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dossier');
            $table->unsignedBigInteger('id_juge')->nullable();
            $table->unsignedBigInteger('id_procureur')->nullable();
            $table->date('date_saisine');
            $table->enum('statut', ['en_cours', 'clos_renvoi', 'clos_non_lieu'])->default('en_cours');
            $table->date('date_cloture')->nullable();
            $table->text('motif_cloture')->nullable();
            $table->foreign('id_dossier')->references('id_dossier')->on('dossiers')->onDelete('cascade');
            $table->foreign('id_juge')->references('id')->on('users')->onDelete('set null');
            $table->foreign('id_procureur')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('actes_instruction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_instruction');
            $table->unsignedBigInteger('id_demandeur')->nullable();
            $table->enum('type_acte', [
                'commission_rogatoire',
                'expertise',
                'audition',
                'confrontation',
                'perquisition',
                'autre',
            ]);
            $table->text('objet');
            $table->date('date_demande');
            $table->date('date_echeance')->nullable();
            $table->enum('statut', ['en_attente', 'en_cours', 'execute', 'annule'])->default('en_attente');
            $table->date('date_execution')->nullable();
            $table->text('resultat')->nullable();
            $table->foreign('id_instruction')->references('id')->on('instructions')->onDelete('cascade');
            $table->foreign('id_demandeur')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('mesures_detention', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_instruction');
            $table->date('date_debut');
            $table->date('date_echeance');
            $table->enum('statut', ['en_cours', 'prolongee', 'levee'])->default('en_cours');
            $table->text('motif');
            $table->foreign('id_instruction')->references('id')->on('instructions')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('messages_instruction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_instruction');
            $table->unsignedBigInteger('expediteur_id');
            $table->string('objet');
            $table->text('contenu');
            $table->boolean('lu')->default(false);
            $table->foreign('id_instruction')->references('id')->on('instructions')->onDelete('cascade');
            $table->foreign('expediteur_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages_instruction');
        Schema::dropIfExists('mesures_detention');
        Schema::dropIfExists('actes_instruction');
        Schema::dropIfExists('instructions');
    }
};
