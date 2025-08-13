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
        Schema::table('projetos', function (Blueprint $table) {
            $table->string('imagem')->nullable(); // Add the 'imagem' column
        });
    }

    public function down()
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->dropColumn('imagem'); // Remove the 'imagem' column
        });
    }
};
