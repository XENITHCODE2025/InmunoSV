<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transcripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('audio_path');
            $table->text('texto_generado')->nullable();
            $table->string('servicio')->default('whisper'); // whisper | google | azure
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transcripciones');
    }
};