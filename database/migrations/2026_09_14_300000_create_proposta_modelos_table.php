<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modelos de proposta: textos que se repetem de cliente para cliente.
     *
     * O contrato de manutenção é o caso típico - muda o nome, o CPF e o
     * domínio, e o resto é sempre igual. Sem isso, cada proposta nova exige
     * reescrever (ou recolar) as mesmas cláusulas.
     *
     * O modelo guarda apenas o conteudo. Valores, cliente e itens continuam
     * sendo preenchidos na proposta.
     */
    public function up(): void
    {
        Schema::create('proposta_modelos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->string('titulo');
            $table->longText('conteudo');
            $table->timestamps();
            $table->softDeletes();

            $table->index('titulo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposta_modelos');
    }
};
