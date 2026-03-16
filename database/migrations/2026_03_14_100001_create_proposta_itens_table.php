<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposta_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained('propostas')->cascadeOnDelete();
            $table->text('descricao');
            $table->decimal('quantidade', 10, 2)->default(1);
            $table->decimal('valor_unitario', 10, 2)->default(0);
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposta_itens');
    }
};
