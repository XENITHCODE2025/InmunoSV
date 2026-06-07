<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('apellido', 100);

            $table->date('fecha_nacimiento');

            $table->enum('genero', [
                'femenino',
                'masculino',
                'otro'
            ])->nullable();

            $table->string('telefono', 9);

            $table->string('departamento', 50);

            $table->string('email')->unique();

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};