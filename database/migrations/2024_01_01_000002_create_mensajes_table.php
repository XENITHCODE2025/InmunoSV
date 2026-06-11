<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversacion_id')->constrained('conversaciones')->onDelete('cascade');
            $table->enum('emisor', ['usuario', 'bot']);
            $table->text('mensaje');
            $table->enum('tipo', ['texto', 'audio'])->default('texto');
            $table->string('audio_path')->nullable(); // ruta del archivo de audio (solo tipo=audio)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};