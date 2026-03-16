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
        Schema::create('propostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            
            $table->string('titulo');
            $table->enum('status', [
                'rascunho', 
                'enviada', 
                'visualizada', 
                'aprovada', 
                'recusada', 
                'cancelada'
            ])->default('rascunho');
            
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->json('dados_faturamento')->nullable()->comment('Divisão de produtos e totais para futura integração.');
            $table->date('data_validade')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propostas');
    }
};
