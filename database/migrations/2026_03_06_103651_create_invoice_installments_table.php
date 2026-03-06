<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->integer('installment_number');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['pendente', 'paga', 'vencida', 'cancelada'])->default('pendente');
            $table->date('payment_date')->nullable();
            $table->string('boleto_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_installments');
    }
};