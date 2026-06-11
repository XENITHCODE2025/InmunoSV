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
        Schema::create('recomendaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vacuna_id');

            $table->string('titulo');
            $table->text('descripcion');

            $table->timestamps();

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
        Schema::dropIfExists('recomendacions');
    }
};
