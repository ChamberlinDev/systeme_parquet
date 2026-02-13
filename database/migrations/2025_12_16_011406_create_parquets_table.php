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
        Schema::create('parquets', function (Blueprint $table) {
            $table->bigIncrements('id_parquet'); // PK
            $table->string('nom_magistrat');
            $table->enum('fonction', ['procureur','substitut']);
            $table->enum('decision_orientation', ['classement','citation','requisitoire','comparution_immediate' ]);
            $table->date('date_decision');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parquets');
    }
};
