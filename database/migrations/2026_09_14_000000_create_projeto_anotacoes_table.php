<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Caderno de anotacoes do projeto.
     *
     * Cada linha e uma anotacao com data e hora, como um diario do projeto.
     * Nao ha edicao: anotacao registrada permanece, e o que da o historico.
     * Para corrigir algo, anota-se de novo.
     */
    public function up(): void
    {
        Schema::create('projeto_anotacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->cascadeOnDelete();
            // Autor e opcional: se o usuario for removido, a anotacao fica
            // sem autor em vez de sumir do historico.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('texto');
            $table->timestamps();

            $table->index(['projeto_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projeto_anotacoes');
    }
};
