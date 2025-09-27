<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('galeria_midias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galeria_id')->constrained('galerias')->onDelete('cascade');
            $table->enum('tipo', ['imagem', 'video']);
            $table->text('caminho');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeria_midias');
    }
};
