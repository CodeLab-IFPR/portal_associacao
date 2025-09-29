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
        Schema::table('galerias', function (Blueprint $table) {
            $table->date('data_inicio_evento')->nullable()->after('descricao');
            $table->date('data_fim_evento')->nullable()->after('data_inicio_evento');
            if (Schema::hasColumn('galerias', 'data_evento')) {
                $table->dropColumn('data_evento');
            }
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galerias', function (Blueprint $table) {
            $table->date('data_evento')->nullable()->after('descricao');
            $table->dropColumn('data_inicio_evento');
            $table->dropColumn('data_fim_evento');
        });
    }
};
