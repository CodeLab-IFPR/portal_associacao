<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->integer('installments_count');
            $table->enum('periodicity', ['semanal', 'quinzenal', 'mensal', 'bimestral', 'trimestral', 'semestral', 'anual']);
            $table->date('first_due_date');
            $table->enum('status', ['pendente', 'paga', 'vencida', 'cancelada'])->default('pendente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};