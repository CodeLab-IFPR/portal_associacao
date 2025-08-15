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
        Schema::table('frase_inicio', function (Blueprint $table) {
            $table->string('titulo')->nullable();
            $table->string('subtitulo')->nullable();
            $table->string('localizacao')->nullable();
            $table->text('descricao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frase_inicio', function (Blueprint $table) {
            $table->dropColumn(['titulo', 'subtitulo', 'localizacao', 'descricao']);
        });
    }
};
