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
        Schema::table('users', function (Blueprint $table) {
            // Modalidade
            $table->enum('modalidade_principal', ['aeromodelismo', 'automodelismo'])->nullable();
            
            // Dados pessoais
            $table->string('nome')->nullable();
            $table->string('sobrenome')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('rg')->nullable();
            
            // Contato
            $table->string('telefone_celular')->nullable();
            $table->boolean('celular_whatsapp')->default(false);
            $table->string('telefone_residencial')->nullable();
            $table->string('telefone_comercial')->nullable();
            $table->string('email_alternativo')->nullable();
            $table->string('senha')->nullable();
            
            // Endereço
            $table->string('cep')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('estado')->nullable();
            $table->string('cidade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'modalidade_principal',
                'nome',
                'sobrenome', 
                'data_nascimento',
                'rg',
                'telefone_celular',
                'celular_whatsapp',
                'telefone_residencial',
                'telefone_comercial',
                'email_alternativo',
                'senha',
                'cep',
                'logradouro',
                'numero',
                'bairro',
                'estado',
                'cidade'
            ]);
        });
    }
};
