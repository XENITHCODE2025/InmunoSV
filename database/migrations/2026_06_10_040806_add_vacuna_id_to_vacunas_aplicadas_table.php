<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacunas_aplicadas', function (Blueprint $table) {
            $table->unsignedBigInteger('vacuna_id')->after('user_id');

            $table->foreign('vacuna_id')
                ->references('id_vacuna')
                ->on('vacunas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('vacunas_aplicadas', function (Blueprint $table) {
            $table->dropForeign(['vacuna_id']);
            $table->dropColumn('vacuna_id');
        });
    }

};
