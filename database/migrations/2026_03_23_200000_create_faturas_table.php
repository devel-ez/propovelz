<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('projeto_id')->nullable()->constrained('projetos')->nullOnDelete();
            $table->enum('tipo', ['unica', 'mensal'])->default('unica');
            $table->text('descricao_servico');
            $table->decimal('valor', 10, 2)->nullable();
            $table->date('mes_referencia')->nullable(); // Para agrupamento mensal (AAAA-MM-01)
            $table->date('vencimento')->nullable();
            $table->boolean('pago')->default(false);
            $table->date('data_pagamento')->nullable();
            $table->text('observacoes')->nullable();
            $table->uuid('grupo_clone')->nullable()->index(); // Agrupa as 12 faturas de um contrato
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};
