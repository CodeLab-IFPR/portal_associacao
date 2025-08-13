<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('lancamento_servicos', function (Blueprint $table) {
            $table->string('descricao', 300)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('lancamento_servicos', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
    }
};
