<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titulo')->default('Nueva conversación');
            $table->string('ultimo_tema')->nullable();       // contexto: último tema tratado
            $table->string('ultima_vacuna')->nullable();     // contexto: última vacuna mencionada
            $table->text('ultima_recomendacion')->nullable(); // contexto: última recomendación
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversaciones');
    }
};