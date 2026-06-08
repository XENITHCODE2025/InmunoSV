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
        Schema::create('encuesta_vacunas', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('encuesta_id');

            $table->unsignedBigInteger('vacuna_id');

            $table->timestamps();

            $table->foreign('encuesta_id')
                ->references('id_encuesta')
                ->on('encuestas_salud')
                ->onDelete('cascade');

            $table->foreign('vacuna_id')
                ->references('id_vacuna')
                ->on('vacunas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuesta_vacunas');
    }
};
